@extends('layouts.app')

@section('content')
    <h3>Editar plano</h3>
    <form class="form-horizontal" enctype="multipart/form-data" action="{{route('administrador.editar_plano')}}" method="post">

        {{csrf_field()}}
        <input type="hidden" name="id" value=" {{$plano->id}} ">
        <div class="form-group">
            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input id="nombre" type="text" name="nombre" value=" {{$plano->nombre}} ">
            </div>
        </div>
        <div class="form-group">
            <label for="imagen" class="col-sm-2 control-label">Imagen</label>
            <div class="col-sm-10">
                <input id="imagen" type="file" name="imagen" value=" {{$plano->imagen}} ">
                ( Si no se introduce una nueva imagen no se modificará la actual )
            </div>
        </div>

        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Editar" class="btn btn-success">
        </div>
    </form>
@endsection