@extends('layouts.app')

@section('content')
    <h3>Editar producto</h3>
    <form class="form-horizontal" action="{{route('administrador.editar_producto')}}" method="post">

        {{csrf_field()}}
        <input type="hidden" name="id" value="{{ $pro->id }}">
        <div class="form-group">
            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input id="nombre" type="text" name="nombre" value="{{ $pro->nombre }}">
            </div>
        </div>
        <div class="form-group">
            <label for="descripcion" class="col-sm-2 control-label">Descripción</label>
            <div class="col-sm-10">
                <input id="descripcion" type="text" name="descripcion" value="{{ $pro->descripcion }}">
            </div>
        </div>
        <div class="form-group">
            <label for="restricciones" class="col-sm-2 control-label">Restricciones</label>
            <div class="col-sm-10">
                <input id="restricciones" type="text" name="restricciones" value="{{ $pro->restricciones }}">
            </div>
        </div>
        <div class="form-group">
            <label for="coste" class="col-sm-2 control-label">Coste</label>
            <div class="col-sm-10">
                <input type="number" min="0" max="10000" step="0.01" value="{{ $pro->coste }}" name="coste" >
            </div>
        </div>
        <div class="form-group">
            <label for="imagen" class="col-sm-2 control-label">Imagen</label>
            <div class="col-sm-10">
                <input id="imagen" type="text" name="imagen" value="{{ $pro->imagen }}">
            </div>
        </div>

        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Editar" class="btn btn-success">
        </div>
    </form>
@endsection