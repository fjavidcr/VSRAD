@extends('layouts.app')

@section('content')
    <h3>Crear usuario</h3>
    <form class="form-horizontal" action="{{route('administrador.crear_usuario')}}" method="post">

        {{csrf_field()}}
        <div class="form-group">
            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input id="name" type="text" name="name">
            </div>
        </div>
        <div class="form-group">
            <label for="apellidos" class="col-sm-2 control-label">Apellidos</label>
            <div class="col-sm-10">
                <input id="apellidos" type="text" name="apellidos">
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input id="email" type="text" name="email">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">Contraseña</label>
            <div class="col-sm-10">
                <input id="password" type="password" name="password">
            </div>
        </div>
        <div class="form-group">
            <label for="direccion_fisica" class="col-sm-2 control-label">Dirección física</label>
            <div class="col-sm-10">
                <input id="direccion_fisica" type="text" name="direccion_fisica">
            </div>
        </div>
        <div class="form-group">
            <label for="telefono" class="col-sm-2 control-label">Teléfono</label>
            <div class="col-sm-10">
                <input id="telefono" type="text" name="telefono">
            </div>
        </div>
        <div class="form-group">
            <label for="rol" class="col-sm-2 control-label">Rol</label>
            <div class="col-sm-10">
                <select name="rol">
                    <option>Seleccione una opción</option>
                    <option value="1">Comercial</option>
                    <option value="2">Técnico</option>
                    <option value="3">Director comercial</option>
                    <option value="4">Administrador</option>
                </select>
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Crear" class="btn btn-success">
        </div>
    </form>
@endsection