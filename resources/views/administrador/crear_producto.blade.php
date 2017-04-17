@extends('layouts.app')

@section('content')
    <h3>Crear producto</h3>
    <form class="form-horizontal" enctype="multipart/form-data" action="{{route('administrador.crear_producto')}}" method="post">

        {{csrf_field()}}
        <div class="form-group">
            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input id="nombre" type="text" name="nombre">
            </div>
        </div>
        <div class="form-group">
            <label for="descripcion" class="col-sm-2 control-label">Descripción</label>
            <div class="col-sm-10">
                <input id="descripcion" type="text" name="descripcion">
            </div>
        </div>
        <div class="form-group">
            <label for="restricciones" class="col-sm-2 control-label">Restricciones</label>
            <div class="col-sm-10">
                <input id="restricciones" type="text" name="restricciones">
            </div>
        </div>
        <div class="form-group">
            <label for="coste" class="col-sm-2 control-label">Coste</label>
            <div class="col-sm-10">
                <input type="number" min="0" max="10000" step="0.01" value="0.00" name="coste" />
            </div>
        </div>
        <div class="form-group">
            <label for="imagen" class="col-sm-2 control-label">Imagen</label>
            <div class="col-sm-10">
                <input id="imagen" type="file" name="imagen">
            </div>
        </div>

        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Crear" class="btn btn-success">
        </div>
    </form>
@endsection