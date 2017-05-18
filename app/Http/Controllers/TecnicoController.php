<?php

namespace App\Http\Controllers;

use \DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $this->validate($request, [
            'estado'=> 'required',
            'configuracion' => 'required'
        ]);

        date_default_timezone_set('Europe/Madrid');

        $proyecto = \App\Proyecto::findOrFail($request->input('id_proyecto'));

        $proyecto->configuracion = $request->input('nueva_configuracion');

        //$fecha_solicitud = DateTime::createFromFormat("d-m-Y H:i:s", $fecha);
        //var_dump($fecha_solicitud);

        $fecha_solicitud = new DateTime($proyecto->fecha_creacion);
        $fecha_respuesta = new DateTime(date("d-m-Y H:i:s"));

        $dif = date_diff($fecha_solicitud, $fecha_respuesta);
        $intervalo = $dif->s + $dif->i*60 + $dif->h*60*60 + $dif->d*24*60*60 + $dif->m*30*24*60*60 + $dif->y*365*24*60*60;

        $proyecto->tiempo_transcurrido = $intervalo;
        $proyecto->fecha_creacion= $fecha_respuesta;
        $proyecto->estado = $request->input('estado');
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

    //FUNCION PARA MOSTAR EL PROYECTOS ASIGNADO
    public function show($id)
    {
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);
        $pros = DB::table('productos')->where('oculto', '=', 0)->get();
        $mensajes = $proyecto->mensajes;

        if ($proyecto->id_tecnico != $user->id)
        {
            Session::flash('Warning', 'No tienes asginado este proyecto.');
            return redirect()->route('tecnico.index');
        }
        return view('tecnico.show', compact('proyecto', 'pros'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);
        $pros = DB::table('productos')->where('oculto', '=', 0)->get();

        if ($proyecto->id_tecnico != $user->id)
        {
            Session::flash('Warning', 'No tienes asginado este proyecto.');
            return redirect()->route('tecnico.index');
        }
        return view('tecnico.proyecto', compact('proyecto', 'pros'));
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

    //FUNCIÓN PARA CAMBIAR EL ESTADO DE LOS PROYECTOS
    public function cambiar_estado(Request $request)
    {
        $user = \Auth::user();
        $id_proyecto = $request->input('id_proyecto');
        $proyecto = \App\Proyecto::findOrFail($id_proyecto);

        if ($proyecto->id_tecnico != $user->id)
        {
            $request->session()->flash('alert-warning', 'No tienes asginado este proyecto, no tienes permiso para validarlo.');
            return redirect()->route('tecnico.index');
        }
        //Valido el proyecto
        $proyecto->configuracion =  $request->input('configuracion');
        $proyecto->estado = $request->input('estado');
        $proyecto->save();
        $request->session()->flash('alert-success', 'Proyecto validado con éxito.');
        return redirect()->route('tecnico.index');
    }

    public function mensajes($id){
        $user = \Auth::user();
        $proyecto = \App\Proyecto::findOrFail($id);
        $mensajes = $proyecto->mensajes;

        return view('tecnico.mensajes', compact('proyecto','mensajes', 'user'));
    }

    public function enviar_mensaje(Request $request)
    {
        $user = \Auth::user();
        $id_proyecto = $request->input('id_proyecto');
        $proyecto = \App\Proyecto::findOrFail($id_proyecto);

        //Guardo el mensaje
        $mensaje = new \App\Mensaje();
        $texto = $request->input('texto');
        $texto[0] = strtoupper($texto[0]);
        $mensaje->texto =  $texto;

        date_default_timezone_set('Europe/Madrid');
        $fecha = new DateTime('now');
        $fecha->format("d-m-Y H:i:s");
        $mensaje->fecha_creacion = $fecha;

        //1 hace referencia al tecnico
        $mensaje->remitente = 1;
        $mensaje->id_proyecto = $id_proyecto;
        $mensaje->id_tecnico = $user->id;
        $mensaje->id_cliente = $proyecto->id_cliente;
        $cliente = \App\User::findOrFail($proyecto->id_cliente);
        $mensaje->id_comercial = $cliente->id_comercial;

        $mensaje->save();
        $request->session()->flash('alert-success', 'Mensaje enviado.');
        return redirect()->route('tecnico.mensajes', $id_proyecto);
    }
}