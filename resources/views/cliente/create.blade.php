@extends('layouts.app')

@section('content')
    <h3>Crear proyecto</h3>
    <form action="{{route('cliente.store')}}" method="post">

        {{csrf_field()}}

        <div class="form-group">
            <label for="nombre">Nombre del proyecto</label>
            <input id="nombre" type="text" name="nombre">
        </div>
        <div class="form-group">
            <label for="configuracion">Configuración</label>
            <textarea id="configuracion" name="configuracion"></textarea>
        </div>
        <input type="submit" value="Crear" class="btn btn-success">
    </form>
@endsection