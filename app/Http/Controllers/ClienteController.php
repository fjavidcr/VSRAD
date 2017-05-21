<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toJSON;
use PhpParser\Node\Expr\Array_;

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
        $prots = $user->proyectos;
        $proyectos = array();
        foreach ($prots as $p)
            if($p->oculto == 0)
                array_push($proyectos, $p);

        return view('cliente.index', compact('proyectos', 'user'));
    }

    public function movil()
    {
        $user = \Auth::user();
        $prots = $user->proyectos;
        $proyectos = array();
        foreach ($prots as $p)
            if($p->oculto == 0)
                array_push($proyectos, $p);

        return view('cliente.movil', compact('proyectos', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $pros = DB::table('productos')->where('oculto', '=', 0)->get();
        $planos = DB::table('planos')->where('oculto', '=', 0)->get();
        $user = \Auth::user();
        return view('cliente.create', compact('pros','planos', 'user'));
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


        date_default_timezone_set('Europe/Madrid');
        $fecha = date("Y-m-d H:i:s");

        $proyecto->fecha_creacion= $fecha;

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
        $planos = DB::table('planos')->where('oculto', '=', 0)->get();
        /*if ($proyecto->cliente_id != $user->id)
            return redirect()->route('cliente.index');*/

        return view('cliente.show', compact('proyecto', 'planos'));
    }

    public function ver_proyecto($id)
    {
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);

        $configuracion = $proyecto->configuracion;

        $json = json_decode($configuracion);

        date_default_timezone_set('Europe/Madrid');
        $hoy = date("Y-m-d H:i:s");
        $pdf = \App::make('dompdf.wrapper');
        $contenido =

            "<head>
    <meta charset=\"utf-8\">
    <title>Proyecto: ". $proyecto->nombre ." </title>
    <link rel=\"stylesheet\" href=\"style.css\" media=\"all\" />
    <link href=\"/css/app.css\" rel=\"stylesheet\"/>
  </head>
  <body>    
    <main>
    <div class='row'>
      <div id=\"logo\">
        <img src=\"logo_ufv.png\" alt=\"Logo UFV\">
        <h3>Proyecto: ". $proyecto->nombre . "</h3>
        <h4>Fecha:  " . $hoy ." </h4>
      </div>
      <div id=\"details\" class=\"clearfix\">
        <div id=\"client\">
          <h2 class=\"name\"> ". $user->name ."</h2>
          <a href=\"mailto:\" . $user->email . \" > " . $user->email . "</a>
        </div>        
      </div>
      <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td class=\"desc\"><h3>Comercial: </h3></td>
            <td class=\"unit\">" . \App\User::getComercial($user->id_comercial) . "</td>
        </tr>
        <tr>
            <td class=\"desc\"><h3>Coste total y descuento aplicado: (sin IVA) </h3></td>
            <td class=\"unit\">" . $proyecto->coste . " &#8364; - " . $proyecto->oferta . "%</td>
        </tr>
        </table>
        <h2 class=\"title\"> Productos del proyecto</h2>";

        foreach ($json->nodeDataArray as $p){
            if($p->id != 0){
                $path = "img/" . $p->imagen;
                $contenido .= "
               
            <img src=\"". $path ."\" class=\"img-thumbnail imagen_pdf\" alt=\"Imagen producto\">
            
            <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"table table-responsive\">
            <tr>
                <td class=\"desc\"><h3>Nombre: </h3></td>
                <td class=\"unit\">" . $p->nombre . "</td>
                </tr>    
            <tr>
                <td class=\"desc\"><h3>Descripción: </h3></td>
                <td class=\"unit\">" . $p->descripcion . "</td>
            </tr>
            <tr>
                <td class=\"desc\"><h3>Restricciones: </h3></td>
                <td class=\"unit\">" . $p->restricciones . "</td>
                </tr>    
            <tr>
                <td class=\"desc\"><h3>Coste: (sin IVA) </h3></td>
                <td class=\"unit\">" . $p->coste . "</td>
            </tr>
            </table>
            </div>
            ";
            }
        }

        $contenido .= "</main></body>";
        $pdf->loadHTML($contenido);
        return $pdf->stream();

        return view('cliente.show_movil', compact('proyecto', 'user'));
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
        $planos = DB::table('planos')->where('oculto', '=', 0)->get();
        return view('cliente.edit', compact('proyecto', 'planos','pros'));
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
    public function destroy(Request $request)
    {
        // Mandar un mensaje de confirmacion

        $proyecto = \App\Proyecto::findOrFail($request->input('id'));
        $proyecto->oculto = 1;
        $proyecto->save();

        // Mensaje de feeback. Un alert de bootstrap

        $request->session()->flash('alert-danger', 'Proyecto eliminado con éxito.');
        return redirect()->route('cliente.index');
    }

    public function cambiar_estado($id)
    {
        $proyecto = \App\Proyecto::findOrFail($id);
        $proyecto->estado = 1;
        date_default_timezone_set('Europe/Madrid');
        $fecha = date("Y-m-d H:i:s");
        $proyecto->fecha_creacion= $fecha;
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

        date_default_timezone_set('Europe/Madrid');
        $fecha = date("Y-m-d H:i:s");

        $user->fecha_registro = $fecha;

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

        $proyecto = \App\Proyecto::findOrFail($request->input('id_proyecto'));

        $proyecto->nombre = $request->input('nombre');
        $proyecto->configuracion = $request->input('nueva_configuracion');
        date_default_timezone_set('Europe/Madrid');
        $fecha = date("Y-m-d H:i:s");

        $proyecto->fecha_creacion= $fecha;
        $proyecto->id_cliente = \Auth::user()->id;
        $proyecto->id_plano = $request->input('id_plano'); // Hay que meter el plano con $proyecto->id_plano = $request->input('id_plano');
        $proyecto->estado = 0;
        $proyecto->coste = $request->input('coste');
        $proyecto->save();

        $request->session()->flash('alert-success', 'Proyecto creado con éxito.');
        return redirect()->route('cliente.index');
    }

    public function comprar(Request $request){
        $proyecto = \App\Proyecto::findOrFail($request->input('id'));
        $proyecto->estado = 4;
        $proyecto->save();
        return redirect()->route('cliente.index');
    }

    public function pedir_presupuesto(Request $request){
        $proyecto = \App\Proyecto::findOrFail($request->input('id'));
        $proyecto->estado = 6;
        $proyecto->save();
        return redirect()->route('cliente.index');
    }

    public function rechazar(Request $request){
        $proyecto = \App\Proyecto::findOrFail($request->input('id'));
        $proyecto->estado = 5;
        $proyecto->save();
        return redirect()->route('cliente.index');
    }

    public function mensajes($id){
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);
        $mensajes = $proyecto->mensajes;

        return view('cliente.mensajes', compact('proyecto','mensajes', 'user'));
    }

    public function mensajes_movil($id){
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);
        $mensajes = $proyecto->mensajes;

        return view('cliente.mensajes_movil', compact('proyecto','mensajes', 'user'));
    }

    public function enviar_mensaje(Request $request)
    {
        $user = \Auth::user();
        $id_proyecto = $request->input('id_proyecto');
        $proyecto = \App\Proyecto::findOrFail($id_proyecto);
        $texto = $request->input('texto');
        if($texto != ""){
            //Guardo el mensaje
            $mensaje = new \App\Mensaje();
            $texto[0] = strtoupper($texto[0]);
            $mensaje->texto =  $texto;

            date_default_timezone_set('Europe/Madrid');
            $fecha = date("Y-m-d H:i:s");
            $mensaje->fecha_creacion = $fecha;

            //0 hace referencia al cliente
            $mensaje->remitente = 0;
            $mensaje->id_proyecto = $id_proyecto;

            $mensaje->id_tecnico = $proyecto->id_tecnico;
            $mensaje->id_cliente = $user->id;
            $mensaje->id_comercial = $user->id_comercial;

            $mensaje->save();
            $request->session()->flash('alert-success', 'Mensaje enviado.');
            return redirect()->route('cliente.mensajes', $id_proyecto);
        }
    }

    public function enviar_mensaje_movil(Request $request)
    {
        $user = \Auth::user();
        $id_proyecto = $request->input('id_proyecto');
        $proyecto = \App\Proyecto::findOrFail($id_proyecto);
        $texto = $request->input('texto');
        if($texto != ""){
            //Guardo el mensaje
            $mensaje = new \App\Mensaje();
            $texto[0] = strtoupper($texto[0]);
            $mensaje->texto =  $texto;

            date_default_timezone_set('Europe/Madrid');
            $fecha = date("Y-m-d H:i:s");
            $mensaje->fecha_creacion = $fecha;

            //0 hace referencia al cliente
            $mensaje->remitente = 0;
            $mensaje->id_proyecto = $id_proyecto;

            $mensaje->id_tecnico = $proyecto->id_tecnico;
            $mensaje->id_cliente = $user->id;
            $mensaje->id_comercial = $user->id_comercial;

            $mensaje->save();
            $request->session()->flash('alert-success', 'Mensaje enviado.');
        }
        return redirect()->route('mensajes_movil', $id_proyecto);
    }
}