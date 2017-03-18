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
        return view('proyectos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|min:5',
            'configuracion' => 'required'
        ]);

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

        $request->session()->flash('alert-success', 'Proyecto creado con éxito.');
        return redirect()->route('proyectos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);

        if ($proyecto->cliente_id != $user->id)
            return redirect()->route('proyectos.index');

        return view('proyectos.show', compact('proyecto'));
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
    public function destroy(Request $request, $id)
    {
        // Mandar un mensaje de confirmacion

        \App\Proyecto::destroy($id);

        // Mensaje de feeback. Un alert de bootstrap

        $request->session()->flash('alert-danger', 'Proyecto eliminado con éxito.');
        return redirect()->route('proyectos.index');
    }

    public function cambiarEstado($id)
    {
        $proyecto = \App\Proyecto::findOrFail($id);

//        if($proyecto->validado == 0)
//            $proyecto->validado = 1;
//        else
//            $proyecto->validado = 0;

//        $proyecto->validado = ($proyecto->validado) ? 0 : 1;

        $proyecto->validado = !$proyecto->validado;

        $proyecto->save();

        return $proyecto;
    }
}
