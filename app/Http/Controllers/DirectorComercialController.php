<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DirectorComercialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $users = \App\User::all();
        $comerciales = array();
        foreach ( $users as $u)
            if ($u->hasRol("comercial"))
                array_push($comerciales, $u);

        return view('director_comercial.index', compact('user', 'comerciales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function asignar_oferta(Request $request)
    {
        $this->validate($request, [
            'oferta' => 'required'
        ]);

        $id = $request->input('id');
        $oferta = $request->input('oferta');

        $comercial = \App\User::findOrFail($id);
        $comercial->oferta = $oferta;
        $comercial->save();

        $request->session()->flash('alert-success', 'Oferta asignada con éxito.');
        return redirect()->route('director_comercial.index');
    }

    public function añadir_cliente(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:16',
            'password' => 'required|min:8',
            'email' => 'required',
            'id_comercial' => 'required'
        ]);

        $cliente = new \App\User();
        $cliente->name = $request->input('name');
        $cliente->email = $request->input('email');
        $cliente->password = Hash::make($request->input('password'));
        $cliente->id_comercial = $request->input('id_comercial');
        $cliente->rol = 0;
        $cliente->save();

        $request->session()->flash('alert-success', 'Cliente añadido con éxito.');
        return redirect()->route('director_comercial.index');
    }

    public function informe_comercial($id){
        $c = \App\User::findOrFail($id);
        $users = \App\User::all();
        $clientes = array();
        $pendientes = array();
        $comprados = array();
        $rechazados = array();
        $hoy = date("d/m/Y");
        foreach ( $users as $u) {
            if ($u->hasRol("cliente") && $u->hasId_comercial($c->id)) {
                array_push($clientes, $u);
                foreach ( $u->proyectos() as $p) {
                    if ($p->getEstado() == "pendiente")
                        array_push($pendientes, $p);
                    elseif ($p->getEstado() == "comprado")
                        array_push($comprados, $p);
                    elseif ($p->getEstado() == "rechazado")
                        array_push($rechazados, $p);
                }
            }
        }
        $media_comprados=0;
        foreach ($comprados as $c)
            $media_comprados += $c->coste;

        if(count($comprados)>0)
        $media_comprados = $media_comprados/count($comprados);
        else
            $media_comprados = "0";

        $media_rechazados=0;
        foreach ($rechazados as $c)
            $media_rechazados += $c->coste;
        if(count($rechazados)>0)
        $media_rechazados = $media_rechazados/count($rechazados);
        else
            $media_rechazados = "0";

        $pdf = \App::make('dompdf.wrapper');

        $contenido =


        "<head>
    <meta charset=\"utf-8\">
    <title>Informe ". $c->name. "</title>
    <link rel=\"stylesheet\" href=\"style.css\" media=\"all\" />
  </head>
  <body>
    <header class=\"clearfix\">
      <div id=\"logo\">
        <img src=\"LogoActioris.png\"><h2>Actioris ".$hoy."</h2>   
      </div>      
      </div>
    </header>
    <main>
      <div id=\"details\" class=\"clearfix\">
        <div id=\"client\">
          <div class=\"to\">Nombre: </div>
          <h2 class=\"name\">". $c->name. "</h2>
          <a href=\"mailto:".$c->email."\">".$c->email."</a>
        </div>        
      </div>
      <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <thead>
          <tr>
            <th class=\"no\">#</th>
            <th class=\"desc\">DESCRIPTION</th>
            <th class=\"unit\">TOTAL</th>
          
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class=\"no\">01</td>
            <td class=\"desc\"><h3>Número Clientes: </h3>Descripcion</td>
            <td class=\"unit\">".count($clientes)."</td>
          </tr>
          <tr>
            <td class=\"no\">02</td>
            <td class=\"desc\"><h3>Número Proyectos pendientes: </h3>Descripcion</td>
            <td class=\"unit\">".count($pendientes)."</td>
          </tr>
          <tr>
            <td class=\"no\">03</td>
            <td class=\"desc\"><h3>Número Proyectos comprados: </h3>Descripcion</td>
            <td class=\"unit\">". count($comprados)."</td>
          </tr>
          <tr>
            <td class=\"no\">04</td>
            <td class=\"desc\"><h3>Número Proyectos rechazados: </h3>Descripcion</td>
            <td class=\"unit\">". count($rechazados)."</td>
          </tr>
          <tr>
            <td class=\"no\">05</td>
            <td class=\"desc\"><h3>Media Proyectos rechazados: </h3>Descripcion</td>
            <td class=\"unit\">". $media_rechazados."</td>
          </tr>
          <tr>
            <td class=\"no\">06</td>
            <td class=\"desc\"><h3>Media Proyectos comprados: </h3>Descripcion</td>
            <td class=\"unit\">". $media_comprados."</td>
          </tr>        
        </tbody>        
      </table>
      <div id=\"thanks\">Muchas Gracias!!</div>
      <div id=\"notices\">
        <div>Advertencia:</div>
        <div class=\"notice\">No se devuelve nada!</div>
      </div>
    </main>
    <footer>
      Gracias.
    </footer>
  </body>"

        ;

        $pdf->loadHTML($contenido);
        return $pdf->stream();
    }
}