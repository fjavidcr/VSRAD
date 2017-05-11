@extends('layouts.app')

@section('content')
    <h3>Editar usuario</h3>

    @if(count($errors))
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{$e}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form name="formulario" class="form-horizontal" action="{{route('administrador.editar_usuario')}}" method="post">

        {{csrf_field()}}
        <input type="hidden" name="id" value="{{$user->id}}">
        <div class="form-group">
            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input id="name" type="text" name="name" value="{{ $user->name }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="apellidos" class="col-sm-2 control-label">Apellidos</label>
            <div class="col-sm-10">
                <input id="apellidos" type="text" name="apellidos" value="{{ $user->apellidos }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input id="email" type="text" name="email" value="{{ $user->email }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">Contraseña</label>
            <div class="col-sm-10">
                <input id="password" type="password" name="password">
                ( Si el campo se deja vacío no se modificará la contraseña )
            </div>
        </div>
        <div class="form-group">
            <label for="direccion_fisica" class="col-sm-2 control-label">Dirección física</label>
            <div class="col-sm-10">
                <input id="direccion_fisica" type="text" name="direccion_fisica" value="{{ $user->direccion_fisica }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="telefono" class="col-sm-2 control-label">Teléfono</label>
            <div class="col-sm-10">
                <input id="telefono" type="text" name="telefono" value="{{ $user->telefono }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="dni" class="col-sm-2 control-label">DNI</label>
            <div class="col-sm-10">
                <input id="dni" type="text" name="dni" value="{{ $user->dni }}" onchange="comprobar()" required>
            </div>
        </div>
        <div class="form-group">
            <label for="rol" class="col-sm-2 control-label">Rol</label>
            <div class="col-sm-10">
                <select name="rol" required>
                    <option>Seleccione una opción</option>
                    <option value="1" @if($user->rol == 1) echo selected @endif>Comercial</option>
                    <option value="2" @if($user->rol == 2) echo selected @endif>Técnico</option>
                    <option value="3" @if($user->rol == 3) echo selected @endif>Director comercial</option>
                    <option value="4" @if($user->rol == 4) echo selected @endif>Administrador</option>
                </select>
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-10">
            <input id="boton" type="submit" value="Editar" class="btn btn-success" disabled>
        </div>
    </form>

    <script>

        function comprobar() {
            var numero
            var letr
            var letra
            var expresion_regular_dni
            var dni = document.getElementById('dni').value;
            dni = dni.toUpperCase();
            console.log(dni);

            expresion_regular_dni = /^\d{8}[a-zA-Z]$/;

            //if(expresion_regular_dni.test (dni) == true){
                numero = dni.substr(0,dni.length-1);
                letr = dni.substr(dni.length-1,1);
                numero = numero % 23;
                letra='TRWAGMYFPDXBNJZSQVHLCKET';
                letra=letra.substring(numero,numero+1);
                if (letra!=letr.toUpperCase()) {
                    document.getElementById('boton').disabled=true;
                    alert('DNI erroneo');
                }
                else
                    document.getElementById('boton').disabled=false;
        }
    </script>

@endsection