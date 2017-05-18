@extends('layouts.app')

@section('content')

    <div class="container container-page">

        <div class="page-header">
            <h3>Crear usuario</h3>
        </div>

    @if(count($errors))
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{$e}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-horizontal" action="{{route('administrador.crear_usuario')}}" method="post">

        {{csrf_field()}}
        <div class="form-group">
            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input id="name" type="text" name="name" required>
            </div>
        </div>
        <div class="form-group">
            <label for="apellidos" class="col-sm-2 control-label">Apellidos</label>
            <div class="col-sm-10">
                <input id="apellidos" type="text" name="apellidos" required>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input id="email" type="text" name="email" required>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">Contraseña</label>
            <div class="col-sm-10">
                <input id="password" type="password" name="password" required>
                <br>
                ( Mínimo 8 caracteres. )
            </div>
        </div>
        <div class="form-group">
            <label for="password2" class="col-sm-2 control-label">Confirma contraseña</label>
            <div class="col-sm-10">
                <input id="password2" type="password" name="password2" onchange="comprobar_pass()" required>
            </div>
        </div>
        <div class="form-group">
            <label for="direccion_fisica" class="col-sm-2 control-label">Dirección física</label>
            <div class="col-sm-10">
                <input id="direccion_fisica" type="text" name="direccion_fisica" required>
            </div>
        </div>
        <div class="form-group">
            <label for="telefono" class="col-sm-2 control-label">Teléfono</label>
            <div class="col-sm-10">
                <input id="telefono" type="text" name="telefono" required>
            </div>
        </div>
        <div class="form-group">
            <label for="dni" class="col-sm-2 control-label">DNI</label>
            <div class="col-sm-10">
                <input id="dni" type="text" name="dni" onchange="comprobar()" required>
            </div>
        </div>
        <div class="form-group">
            <label for="rol" class="col-sm-2 control-label">Rol</label>
            <div class="col-sm-10">
                <select id="rol" name="rol" onload="comprobar_rol()" onchange="comprobar_rol()" required>
                    <option>Seleccione una opción</option>
                    <option value="1">Comercial</option>
                    <option value="2">Técnico</option>
                    <option value="3">Director comercial</option>
                    <option value="4">Administrador</option>
                </select>
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-10">
            <input id="boton" type="submit" value="Crear" class="btn btn-success" disabled>
        </div>
    </form>
    </div>

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

        function comprobar_rol() {
            var value = document.getElementById('rol').value;
            console.log(value);

            if (value > 0) {
                document.getElementById('boton').disabled=false;
            }
            else
                document.getElementById('boton').disabled=true;
        }

        function comprobar_tamano() {
            var password2 = document.getElementById('password2').value;
            var password = document.getElementById('password').value;

            if(password.length >= 8 )
                return true;

            return false;
        }

        function comprobar_pass() {
            var password2 = document.getElementById('password2').value;
            var password = document.getElementById('password').value;
            console.log('pass 1 '+password);
            console.log('pass 2 '+password2);

            if ((password == password2) && comprobar_tamano()){
                comprobar_rol();
            }
            else{
                alert('La contraseña no coincide o es demasiado corta.');
                document.getElementById('boton').disabled=true;
            }
        }
    </script>
@endsection