@extends('layouts.app')

@section('content')

    <div class="container container-page">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('tecnico.index') }}"> &lt; Volver a los proyectos asignados</a>

                <h3>{{ $proyecto->nombre }}</h3>
                <form action="{{ route('tecnico.cambiar_estado') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_proyecto" value="{{$proyecto->id}}">
                    <div class="form-group">
                        <h4>Configuración</h4>
                        <textarea id="configuracion" name="configuracion">{{ $proyecto->configuracion }}</textarea>
                    </div>
                    <div class="form-group">
                        <h4>Estado del proyecto</h4>
                        <select name="estado">
                            <option>Seleccione una opción</option>
                            <option value="2">Validar</option>
                            <option value="3">Rechazar</option>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-success" value="Enviar">
                </form>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12">
                <!--- PANEL DONDE APARECEN LOS MENSAJES--->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Mensajes</h3>
                    </div>
                    <div class="panel-body">
                        @if(count($mensajes) == 0)
                            <div class="alert alert-info">
                                No hay ningún mensaje.
                            </div>
                        @else
                            @foreach($mensajes as $m)
                                {{$m->texto}}
                            @endforeach
                        @endif
                    </div>
                </div>
                <!--- FORMULARIO PARA ENVIAR MENSAJES--->
                <form action="{{ route('tecnico.enviar_mensaje') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_proyecto" value="{{$proyecto->id}}">
                    <div class="form-inline">
                        <input id="texto" type="text" name="texto" value="Escribe aquí...">
                        <input type="submit" class="btn btn-primary" value="Enviar">
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection