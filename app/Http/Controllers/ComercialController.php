<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComercialController extends Controller
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
        //$users = DB::table('tabla')->select('columna')->get();
        $clientes = array();
        $tecnicos = array();
        foreach ( $users as $u) {
            if ($u->hasRol("cliente") && $u->hasId_comercial($user->id)) {
                array_push($clientes, $u);
            }
            elseif ($u->hasRol("tecnico")) {
                array_push($tecnicos, $u);
            }
        }
        return view('comercial.index', compact('clientes', 'tecnicos' , 'user'));
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

    public function redireccion()
    {
        return redirect()->route('comercial.index');
    }

    public function asignar_tecnico(Request $request)
    {
        $this->validate($request, [
            'id_tecnico' => 'required'
        ]);

        $id_proyecto = $request->input('id_proyecto');
        $id_tecnico = $request->input('id_tecnico');

        $proyecto = \App\Proyecto::findOrFail($id_proyecto);
        $proyecto->id_tecnico = $id_tecnico;
        $proyecto->update($request->all());

        $request->session()->flash('alert-success', 'Técnico asignado con éxito.');
        return redirect()->route('comercial.index');
    }

    public function asignar_oferta(Request $request)
    {
        $this->validate($request, [
            'oferta' => 'required'
        ]);

        $id_proyecto = $request->input('id_proyecto');
        $oferta = $request->input('oferta');

        $proyecto = \App\Proyecto::findOrFail($id_proyecto);
        $proyecto->oferta = $oferta;
        $proyecto->update($request->all());

        $request->session()->flash('alert-success', 'Oferta asignada con éxito.');
        return redirect()->route('comercial.index');
    }
}
