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
            $media_comprados = "No hay comprados";

        $media_rechazados=0;
        foreach ($rechazados as $c)
            $media_rechazados += $c->coste;
        if(count($rechazados)>0)
        $media_rechazados = $media_rechazados/count($rechazados);
        else
            $media_rechazados = "No hay rechazados";





        $pdf = \App::make('dompdf.wrapper');
        $contenido = "<h1>Informe</h1> 
<img src=\"<?=Image::url('/img/LogoActioris.png',300,300,array('crop','grayscale'))?>\" />

<h2>Nombre del comercial : ". $c->name. "</h2><ul><li>Número Clientes: ". count($clientes)
            ."</li><li>Número Proyectos pendientes: ". count($pendientes)
            ."</li><li>Número Proyectos comprados: ". count($comprados)
            ."</li><li>Número Proyectos rechazados: ". count($rechazados)
            ."</li> <li>Media Proyectos rechazados: ". $media_rechazados
            ."</li> <li>Media Proyectos comprados: ". $media_comprados ."</li></ul> ";


        $pdf->loadHTML($contenido);
        return $pdf->stream();
    }
}
