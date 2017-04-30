<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $pros = DB::table('productos')->where('oculto', '=', 0)->get();
        $user = \Auth::user();
        return view('cliente.create', compact('pros', 'user'));
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
        $fecha = getdate();

        $fecha_creacion = $fecha["mday"] .'/'. $fecha["mon"] .'/'. $fecha["year"];

        $proyecto->fecha_creacion= $fecha_creacion;
        $proyecto->id_cliente = \Auth::user()->id;
        $proyecto->id_plano = $request->input('id_plano'); // Hay que meter el plano con $proyecto->id_plano = $request->input('id_plano');
        $proyecto->estado = 0;
        $proyecto->coste = $request->input('coste');
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
        $pros = DB::table('productos')->where('oculto', '=', 0)->get();
        return view('cliente.edit', compact('proyecto', 'pros'));
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

    public function completar_registro(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:16',
            'email' => 'required|min:8',
            'apellidos' => 'required|max:32',
            'direccion_fisica' => 'required',
            'telefono' => 'required|min:9',
            'dni' => 'required|min:9'
        ]);

        $user = \Auth::user();
        $user->name = $request->input('name');
        $user->apellidos = $request->input('apellidos');
        $user->email = $request->input('email');
        /*
        $pass = $request->input('password');
        if(isset($pass))
            $user->password =  Hash::make($pass);
        */
        $user->direccion_fisica = $request->input('direccion_fisica');
        $user->telefono = $request->input('telefono');

        $fecha = getdate();
        $fecha_registro = $fecha["mday"] .'/'. $fecha["mon"] .'/'. $fecha["year"];
        $user->fecha_registro = $fecha_registro;

        $dni = $request->input('dni');

        /*
         * Comprobación de la letra del DNI
         * */

        $dni = strtoupper($dni);
        $letra = substr($dni, -1, 1);
        $numero = substr($dni, 0, 8);

        // Si es un NIE hay que cambiar la primera letra por 0, 1 ó 2 dependiendo de si es X, Y o Z.
        $numero = str_replace(array('X', 'Y', 'Z'), array(0, 1, 2), $numero);

        $modulo = $numero % 23;
        $letras_validas = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letra_correcta = substr($letras_validas, $modulo, 1);

        if($letra_correcta==$letra) {
            $user->dni = $dni;
            $user->save();
            $request->session()->flash('alert-success', 'Usuario editado con éxito.');
            $proyecto = \App\Proyecto::findOrFail($request->input('id_proyecto'));
            $proyecto->estado = 1;
            $proyecto->save();
        }
        else{
            $request->session()->flash('alert-warning', 'DNI incorrecto.');
            return redirect()->back();
        }

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