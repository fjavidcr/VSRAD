@extends('layouts.app')

@section('content')

    <div class="container container-page">
        <div class="row">
            <div class="col-lg-12">

                <a href="{{ route('cliente.index') }}"> &lt; Volver a la lista</a>

                <h3>{{ $proyecto->nombre }}</h3>

                <p>{{$proyecto->configuracion}}</p>

                <a href="{{ route('cliente.destroy', $proyecto->id) }}" data-id="{{$proyecto->id}}" class="btn btn-danger">Eliminar</a>

            </div>
        </div>
    </div>

@endsection