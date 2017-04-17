@extends('layouts.app')

@section('content')
    <h3>Editar producto</h3>

    @if(count($errors))
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{$e}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-horizontal" enctype="multipart/form-data" action="{{route('administrador.editar_producto')}}" method="post">

        {{csrf_field()}}
        <input type="hidden" name="id" value="{{ $pro->id }}">
        <div class="form-group">
            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input id="nombre" type="text" name="nombre" value="{{ $pro->nombre }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="descripcion" class="col-sm-2 control-label">Descripción</label>
            <div class="col-sm-10">
                <input id="descripcion" type="text" name="descripcion" value="{{ $pro->descripcion }}" required>
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
                <input type="number" min="0" max="10000" step="0.01" value="{{ $pro->coste }}" name="coste" required>
            </div>
        </div>
        <div class="form-group">
            <label for="imagen" class="col-sm-2 control-label">Imagen</label>
            <div class="col-sm-10">
                <input id="imagen" type="file" name="imagen" value="{{ $pro->imagen }}">
                ( Si no se introduce una nueva imagen no se modificará la actual )
            </div>
        </div>

        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Editar" class="btn btn-success">
        </div>
    </form>
@endsection