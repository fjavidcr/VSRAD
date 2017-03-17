@extends('layouts.app')

@section('content')

    <div class="container container-page">
        <div class="row">
            <div class="col-lg-12">

                <a href="{{ route('proyectos.index') }}"> &lt; Volver a la lista</a>

                <h3>{{ $proyecto->nombre }}</h3>

                <p>{{$proyecto->configuracion}}</p>

                <form action="{{ route('proyectos.destroy', $proyecto->id) }}" method="post">

                    {{ csrf_field() }}

                    <input type="text" name="_method" value="delete">

                    <input type="submit" class="btn btn-danger" value="Eliminar">
                </form>

            </div>
        </div>
    </div>

@endsection