<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
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

        return view('cliente.index', compact('proyectos', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productos = \App\Producto::all();
        return view('cliente.create', compact('productos', 'planos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|min:3|max:16',
            'configuracion' => 'required'
        ]);

        $proyecto = new \App\Proyecto();

        $proyecto->nombre = $request->input('nombre');
        $proyecto->configuracion = $request->input('configuracion');
        $proyecto->fecha_creacion= "01/01/01";
        $proyecto->id_cliente = \Auth::user()->id;
        $proyecto->id_plano = 0; // Hay que meter el plano con $proyecto->id_plano = $request->input('id_plano');
        $proyecto->estado = 0;
        $proyecto->save();

        $request->session()->flash('alert-success', 'Proyecto creado con éxito.');
        return redirect()->route('cliente.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);

        /*if ($proyecto->cliente_id != $user->id)
            return redirect()->route('cliente.index');*/

        return view('cliente.show', compact('proyecto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proyecto = \App\Proyecto::findOrFail($id);
        return view('cliente.edit', compact('proyecto'));
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
    public function destroy(Request $request, $id)
    {
        // Mandar un mensaje de confirmacion

        \App\Proyecto::destroy($id);

        // Mensaje de feeback. Un alert de bootstrap

        $request->session()->flash('alert-danger', 'Proyecto eliminado con éxito.');
        return redirect()->route('cliente.index');
    }

    public function cambiar_estado($id)
    {
        $proyecto = \App\Proyecto::findOrFail($id);
        $proyecto->estado = 1;
        $proyecto->save();

        return redirect()->route('cliente.index');
    }

    public function editar(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|min:3|max:16',
            'configuracion' => 'required'
        ]);

        $proyecto = new \App\Proyecto();

        $proyecto->nombre = $request->input('nombre');
        $proyecto->configuracion = $request->input('configuracion');
        $hoy = getdate();
        return $hoy;
        $proyecto->fecha_creacion= $hoy;
        $proyecto->id_cliente = \Auth::user()->id;
        $proyecto->id_plano = 0; // Hay que meter el plano con $proyecto->id_plano = $request->input('id_plano');
        $proyecto->estado = 0;
        $proyecto->save();

        $request->session()->flash('alert-success', 'Proyecto creado con éxito.');
        return redirect()->route('cliente.index');
    }
}