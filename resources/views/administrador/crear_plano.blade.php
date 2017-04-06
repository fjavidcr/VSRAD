@extends('layouts.app')

@section('content')
    <h3>Crear plano</h3>
    <form class="form-horizontal" action="{{route('administrador.crear_plano')}}" method="post">

        {{csrf_field()}}
        <div class="form-group">
            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input id="nombre" type="text" name="nombre">
            </div>
        </div>
        <div class="form-group">
            <label for="imagen" class="col-sm-2 control-label">Imagen</label>
            <div class="col-sm-10">
                <input id="imagen" type="text" name="imagen">
            </div>
        </div>

        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Crear" class="btn btn-success">
        </div>
    </form>
@endsection