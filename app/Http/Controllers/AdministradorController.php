<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministradorController extends Controller
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
        $productos = \App\Producto::all();
        $planos = \App\Plano::all();
        //$users = DB::table('tabla')->select('columna')->get();

        return view('administrador.index', compact('users','productos','planos' , 'user' ));
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

    public function crear_usuario(Request $request){
        $this->validate($request, [
            'name' => 'required|min:3|max:16',
            'password' => 'required|min:8',
            'email' => 'required',
            'apellidos' => 'required|max:32',
            'direccion_fisica' => 'required',
            'telefono' => 'required|min:9',
            'rol' => 'required',
            'dni' => 'required|min:9'
        ]);

        $user = new \App\User();

        $user->name = $request->input('name');
        $user->apellidos = $request->input('apellidos');
        $user->email = $request->input('email');
        $user->password =  Hash::make($request->input('password'));
        $user->direccion_fisica = $request->input('direccion_fisica');
        $user->telefono = $request->input('telefono');
        $user->rol = $request->input('rol');
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
            return redirect()->route('administrador.index');
            $request->session()->flash('alert-success', 'Usuario creado con éxito.');
            //return "Letra incorrecta, la letra deber&iacute;a ser la $letra_correcta.";
        }
        else{
            $request->session()->flash('alert-warning', 'DNI incorrecto.');
            return redirect()->back();
        }
    }

    public function form_crear_usuario(){
        return view('administrador.crear_usuario');
    }

    public function editar_usuario(Request $request){
        $this->validate($request, [
            'name' => 'required|min:3|max:16',
            'email' => 'required|min:8',
            'apellidos' => 'required|max:32',
            'direccion_fisica' => 'required',
            'telefono' => 'required|min:9',
            'dni' => 'required|min:9'
        ]);

        $user = \App\User::findOrFail($request->input('id'));

        $user->name = $request->input('name');
        $user->apellidos = $request->input('apellidos');
        $user->email = $request->input('email');
        $pass = $request->input('password');
        if(isset($pass))
            $user->password =  Hash::make($pass);
        $user->direccion_fisica = $request->input('direccion_fisica');
        $user->telefono = $request->input('telefono');
        $rol = $request->input('rol');
        if(isset($rol))
            $user->rol =$rol;
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
            return redirect()->route('administrador.index');
            $request->session()->flash('alert-success', 'Usuario editado con éxito.');
            //return "Letra incorrecta, la letra deber&iacute;a ser la $letra_correcta.";
        }
        else{
            $request->session()->flash('alert-warning', 'DNI incorrecto.');
            return redirect()->back();
        }
    }

    public function form_editar_usuario($id)
    {
        $user = \App\User::findOrFail($id);

        return view('administrador.editar_usuario', compact('user'));
    }

    public function crear_producto(Request $request){
        $this->validate($request, [
            'nombre' => 'required|min:3|max:16',
            'descripcion' => 'required|max:120',
            'restricciones' => 'max:120',
            'coste' => 'required',
            'imagen' => 'required'
        ]);

        $producto = new \App\Producto();

        $producto->nombre = $request->input('nombre');
        $producto->descripcion = $request->input('descripcion');
        $producto->restricciones = $request->input('restricciones');
        $producto->coste =  $request->input('coste');
        if ($request->file('imagen')->isValid()) {
            /*'productos' -> subcarpeta donde se guarda
             * '' -> nombre con el que se guarda
             * 'img' -> se guarda dentro del path storage/app/public/img
             * */
            $path = $request->imagen->store('productos','img');
            $producto->imagen =  $path;
        }
        $producto->save();

        $request->session()->flash('alert-success', 'Producto creado con éxito.');
        return redirect()->route('administrador.index');
    }

    public function form_crear_producto(){
        return view('administrador.crear_producto');
    }

    public function editar_producto(Request $request){
        $this->validate($request, [
            'nombre' => 'required|min:3|max:16',
            'descripcion' => 'required|max:120',
            'restricciones' => 'max:120',
            'coste' => 'required'
        ]);

        $producto = \App\Producto::findOrFail($request->input('id'));

        $producto->nombre = $request->input('nombre');
        $producto->descripcion = $request->input('descripcion');
        $producto->restricciones = $request->input('restricciones');
        $producto->coste =  $request->input('coste');
        $imagen = $request->file('imagen');
        if(isset($imagen))
            if ($imagen->isValid()) {
                /*'productos' -> subcarpeta donde se guarda
                 * '' -> nombre con el que se guarda
                 * 'img' -> se guarda dentro del path storage/app/public/img
                 * */
                $path = $request->imagen->store('productos','img');
                $producto->imagen =  $path;
            }
        $producto->save();

        $request->session()->flash('alert-success', 'Producto editado con éxito.');
        return redirect()->route('administrador.index');
    }

    public function form_editar_producto($id)
    {
        $pro = \App\Producto::findOrFail($id);

        return view('administrador.editar_producto', compact('pro'));
    }

    public function crear_plano(Request $request){
        $this->validate($request, [
            'nombre' => 'required|min:3|max:16',
            'imagen' => 'required'
        ]);

        $plano = new \App\Plano();

        $plano->nombre = $request->input('nombre');
        $imagen = $request->file('imagen');
        if(isset($imagen))
            if ($imagen->isValid()) {
            /*'productos' -> subcarpeta donde se guarda
             * '' -> nombre con el que se guarda
             * 'img' -> se guarda dentro del path storage/app/public/img
             * */
            $path = $request->imagen->store('planos','img');
            $plano->imagen =  $path;
        }
        $plano->save();

        $request->session()->flash('alert-success', 'Plano creado con éxito.');
        return redirect()->route('administrador.index');
    }

    public function form_crear_plano(){
        return view('administrador.crear_plano');
    }

    public function editar_plano(Request $request){
        $this->validate($request, [
            'nombre' => 'required|min:3|max:16',
        ]);

        $plano = \App\Plano::findOrFail($request->input('id'));

        $plano->nombre = $request->input('nombre');
        if ($request->file('imagen')->isValid()) {
            /*'productos' -> subcarpeta donde se guarda
             * '' -> nombre con el que se guarda
             * 'img' -> se guarda dentro del path storage/app/public/img
             * */
            $path = $request->imagen->store('planos','img');
            $plano->imagen =  $path;
        }
        $plano->save();

        $request->session()->flash('alert-success', 'Plano editado con éxito.');
        return redirect()->route('administrador.index');
    }

    public function form_editar_plano($id)
    {
        $plano = \App\Plano::findOrFail($id);

        return view('administrador.editar_plano', compact('plano'));
    }

    public function deshabilitar_usuario(Request $request){
        $user = \App\User::findOrFail($request->input('id'));
        $user->oculto = 1;
        $user->password = '';
        $user->save();

        return redirect()->route('administrador.index');
    }

    public function habilitar_usuario(Request $request){
        $this->validate($request, [
            'password' => 'required|min:8'
        ]);

        $user = \App\User::findOrFail($request->input('id'));
        $user->oculto = 0;
        $pass = $request->input('password');
        if(isset($pass))
            $user->password =  Hash::make($pass);
        $user->save();

        return redirect()->route('administrador.index');
    }

    public function deshabilitar_producto(Request $request){
        $producto = \App\Producto::findOrFail($request->input('id'));
        $producto->oculto = 1;
        $producto->save();

        return redirect()->route('administrador.index');
    }

    public function habilitar_producto(Request $request){
        $producto = \App\Producto::findOrFail($request->input('id'));
        $producto->oculto = 0;
        $producto->save();

        return redirect()->route('administrador.index');
    }

    public function deshabilitar_plano(Request $request){
        $plano = \App\Plano::findOrFail($request->input('id'));
        $plano->oculto = 1;
        $plano->save();

        return redirect()->route('administrador.index');
    }

    public function habilitar_plano(Request $request){
        $plano = \App\Plano::findOrFail($request->input('id'));
        $plano->oculto = 0;
        $plano->save();

        return redirect()->route('administrador.index');
    }
}