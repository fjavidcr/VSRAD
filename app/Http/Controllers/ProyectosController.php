<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $proyectos = $user->proyectos;
        return view('proyectos.index', compact('proyectos', 'user'));
        /*
         * Otra forma
         * return view('proyectos.index', compact('proyectos');
         * return view('proyectos.index')->with('proyectos', $proyectos);
         * $nombre = "eregerge"
         * */
        //return $user->proyectos;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $proyecto = new \App\Proyecto();

        $proyecto->nombre = $request->input('nombre');
        $proyecto->configuracion = $request->input('configuracion');
        $proyecto->estado = false;
        $proyecto->coste = 0;
        $proyecto->fecha_creacion= "01/01/01";
        $proyecto->id_cliente = \Auth::user()->id;
        $proyecto->id_plano = 0;
        $proyecto->id_tecnico = 0;
        $proyecto->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
