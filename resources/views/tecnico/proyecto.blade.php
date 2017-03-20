@extends('layouts.app')

@section('content')

    <div class="container container-page">
        <div class="row">
            <div class="col-lg-12">

                <a href="{{ route('tecnico.index') }}"> &lt; Volver a los proyectos asignados</a>

                <h3>{{ $proyecto->nombre }}</h3>

                <p>{{ $proyecto->configuracion }}</p>

                <form action="{{ route('tecnico.proyecto.validar', $proyecto->id) }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_proyecto" value="{{$proyecto->id}}">
                    <input type="submit" class="btn btn-success" value="Validar">
                </form>

            </div>
        </div>
    </div>

@endsection