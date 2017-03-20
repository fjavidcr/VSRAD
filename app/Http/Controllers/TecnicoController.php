<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TecnicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $proyectos = $user->proyectos_tecnico;
        return view('tecnico.index', compact('proyectos', 'user'));
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

    //FUNCION PARA MOSTAR LOS PROYECTOS ASIGNADOS AL TÉCNICO
    public function show($id)
    {
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);

        if ($proyecto->id_tecnico != $user->id)
        {
            Session::flash('Warning', 'No tienes asginado este proyecto.');
            return redirect()->route('tecnico.index');
        }
        return view('tecnico.proyecto', compact('proyecto'));
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

    //FUNCIÓN PARA VALIDAR LOS PROYECTOS
    public function validar_proyecto($id)
    {
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);

        if ($proyecto->id_tecnico != $user->id)
        {
            Session::flash('Warning', 'No tienes asginado este proyecto, no tienes permiso para validarlo.');
            return redirect()->route('tecnico.index');
        }
        //Valido el proyecto
        $proyecto->estado = 2;
        $proyecto->save();
        Session::flash('Success', 'Proyecto validado con éxito.');
    }
}
