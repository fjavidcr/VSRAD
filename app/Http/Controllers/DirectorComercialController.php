<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        date_default_timezone_set('Europe/Madrid');
        $hoy = date("d-m-Y H:i:s");

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

        $pdf = \App::make('dompdf.wrapper');
        $cont = 1;
        $contenido =


        "<head>
    <meta charset=\"utf-8\">
    <title>Informe ". $c->name. "</title>
    <link rel=\"stylesheet\" href=\"style.css\" media=\"all\" />
  </head>
  <body>
    <header class=\"clearfix\">
      <div id=\"logo\">
        <img src=\"logo_ufv.png\"><h2>Fecha: ".$hoy."</h2>   
      </div>      
      </div>
    </header>
    <main>
      <div id=\"details\" class=\"clearfix\">
        <div id=\"client\">
          <div class=\"to\">Nombre: </div>
          <h2 class=\"name\">". $c->name. " ". $c->apellidos."</h2>
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
            <td class=\"desc\"><h3>Número clientes: </h3>Número de clientes que tiene asignado el comercial.</td>
            <td class=\"unit\">".count($clientes)."</td>
          </tr>
          <tr>
            <td class=\"no\">02</td>
            <td class=\"desc\"><h3>Número proyectos pendientes: </h3>Número de proyectos que han pedido validación y todavía no han sido validados por el técnico.</td>
            <td class=\"unit\">".count($pendientes)."</td>
          </tr>
          <tr>
            <td class=\"no\">03</td>
            <td class=\"desc\"><h3>Número proyectos comprados: </h3>Número de proyectos comprados por los clientes.</td>
            <td class=\"unit\">". count($comprados)."</td>
          </tr>
          <tr>
            <td class=\"no\">04</td>
            <td class=\"desc\"><h3>Número proyectos rechazados: </h3>Número de proyectos que han rechazado los clientes.</td>
            <td class=\"unit\">". count($rechazados)."</td>
          </tr>
          <tr>
            <td class=\"no\">05</td>
            <td class=\"desc\"><h3>Coste medio proyectos rechazados: </h3>Coste medio de los proyectos rechazados.</td>
            <td class=\"unit\">". \App\User::media_proyectos_rechazados($c->id)." &#8364;</td>
          </tr>
          <tr>
            <td class=\"no\">06</td>
            <td class=\"desc\"><h3>Coste medio proyectos comprados: </h3>Coste medio de los proyectos comprados.</td>
            <td class=\"unit\">". \App\User::media_proyectos_comprados($c->id)."  &#8364;</td>
          </tr>        
        </tbody>        
      </table>
      <footer>
        pág ". $cont . "
       </footer>
    </main>
  </body>
  "

        ;

        $user = \Auth::user();
        $informe = new \App\Informe();

        $informe->id_director = $user->id;
        $informe->texto = $contenido;

        date_default_timezone_set('Europe/Madrid');
        $fecha = date("d-m-Y H:i:s");
        $informe->fecha_creacion = $fecha;

        $informe->nombre =  'Informe - ' .$c->getCompleteName();

        $informe->save();

        $pdf->loadHTML($contenido);
        return $pdf->stream();
    }

    public function informes()
    {
        $user = \Auth::user();
        $users = \App\User::all();
        $informes = DB::table('informes')->where('id_director', '=', $user->id)->get();
        $comerciales = array();
        $clientes = array();
        foreach ( $users as $u) {
            if ($u->hasRol("comercial"))
                array_push($comerciales, $u);
            elseif ($u->hasRol("cliente"))
                array_push($clientes, $u);
        }
        return view('director_comercial.informes', compact('user', 'comerciales', 'clientes', 'informes'));
    }

    public function ver_informe($id)
    {
        $informe = \App\Informe::findOrFail($id);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($informe->texto);

        return $pdf->stream();
    }

    public function informe_todos_comerciales(){
        $comerciales = DB::table('users')->where('rol', '=', 1)->get();
        date_default_timezone_set('Europe/Madrid');
        $hoy = date("d-m-Y H:i:s");
        $pdf = \App::make('dompdf.wrapper');
        $cont = 1;
        $contenido =

            "<head>
    <meta charset=\"utf-8\">
    <title>Informe de comerciales </title>
    <link rel=\"stylesheet\" href=\"style.css\" media=\"all\" />
    <link href=\"/css/app.css\" rel=\"stylesheet\"/>
  </head>
  <body>    
    <main>";

        foreach ($comerciales as $c){
            $contenido .= "
    <div class='row'>
      <div id=\"logo\">
        <img src=\"logo_ufv.png\"><h2>Fecha: " . $hoy . "</h2>
      </div>
      <div id=\"details\" class=\"clearfix\">
        <div id=\"client\">
          <div class=\"to\">Nombre: </div>
          <h2 class=\"name\">" . $c->name . " ". $c->apellidos."</h2>
          <a href=\"mailto:" . $c->email . "\">" . $c->email . "</a>
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
            <td class=\"desc\"><h3>Número de clientes asignados: </h3>Número de clientes que tiene asignado el comercial.</td>
            <td class=\"unit\">" . \App\User::numero_clientes_comercial($c->id) . "</td>
          </tr>
          <tr>
            <td class=\"no\">02</td>
            <td class=\"desc\"><h3>Número de clientes invitados: </h3>Número de clientes que no han completado su registro en la aplicación.</td>
            <td class=\"unit\">" . \App\User::numero_clientes_invitados($c->id) . "</td>
          </tr>
          <tr>
            <td class=\"no\">03</td>
            <td class=\"desc\"><h3>Número de clientes registrados: </h3>Número de clientes que han completado su registro en la aplicación.</td>
            <td class=\"unit\">" . \App\User::numero_clientes_resgistrados($c->id) . "</td>
          </tr>
          <tr>
            <td class=\"no\">04</td>
            <td class=\"desc\"><h3>Número de clientes con proyectos pendientes de validación: </h3>Número de clientes que tienen al menos un proyecto de validación.</td>
            <td class=\"unit\">" . \App\User::numero_proyectos_no_validados($c->id) . "</td>
          </tr>
          <tr>
            <td class=\"no\">05</td>
            <td class=\"desc\"><h3>Número de clientes con todos sus proyectos validados: </h3>Número de clientes que tienen todos sus proyectos validados.</td>
            <td class=\"unit\">" . \App\User::numero_proyectos_validados($c->id) . "</td>
          </tr>         
          <tr>
            <td class=\"no\">06</td>
            <td class=\"desc\"><h3>Coste medio de los proyectos rechazados: </h3>Coste medio de los proyectos rechazados, pertenecientes a los clientes asignados a este comercial.</td>
            <td class=\"unit\">" . \App\User::media_proyectos_rechazados($c->id) . " &#8364;</td>
          </tr>
          <tr>
            <td class=\"no\">07</td>
            <td class=\"desc\"><h3>Coste medio de los proyectos comprados: </h3>Coste medio de los proyectos comprados, pertenecientes a los clientes asignados a este comercial.</td>
            <td class=\"unit\">" . \App\User::media_proyectos_comprados($c->id) . " &#8364;</td>
          </tr>
          <tr>
            <td class=\"no\">08</td>
            <td class=\"desc\"><h3>Tiempo medio desde que se pide la validación hasta que se da respuesta: </h3>Tiempo medio desde que cada uno de los proyectos de los clientes asignados al comercial pide la validación hasta que obtiene respuesta del técnico.</td>
            <td class=\"unit\">" . \App\User::tiempo_medio_comercial($c->id) . "</td>
          </tr>
        </table>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <footer>
        pág ". $cont . "
       </footer>
       
    ";
        $cont++;
        }

        $contenido .= "</main></body>";

        //PARA GUARDAR EL INFORME

        $user = \Auth::user();
        $informe = new \App\Informe();

        $informe->id_director = $user->id;
        $informe->texto = $contenido;
        date_default_timezone_set('Europe/Madrid');
        $fecha = date("d-m-Y H:i:s");

        $informe->fecha_creacion = $fecha;

        $informe->nombre =  'Informe - Comerciales'; //CAMBIAR EL NOMBRE DEL INFORME SEGUN CORRESPONDA

        $informe->save();

        //FIN DE GUARDAR EL INFORME

        $pdf->loadHTML($contenido);
        return $pdf->stream();
    }
}