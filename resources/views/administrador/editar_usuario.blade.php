@extends('layouts.app')

@section('content')
    <div class="container container-page">

        <div class="page-header">
            <h3>Editar usuario</h3>
        </div>
        <div class="col-lg-8">
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
                    <input id="name" type="text" name="name" value="{{ $user->name }}" onchange="comprobar()" required>
                </div>
            </div>
            <div class="form-group">
                <label for="apellidos" class="col-sm-2 control-label">Apellidos</label>
                <div class="col-sm-10">
                    <input id="apellidos" type="text" name="apellidos" value="{{ $user->apellidos }}" onchange="comprobar()" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input id="email" type="text" size="25" name="email" value="{{ $user->email }}" onchange="comprobar()" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Contraseña</label>
                <div class="col-sm-10">
                    <input id="password" type="password" name="password" onchange="comprobar()">
                    <br>
                    ( Mínimo 8 caracteres. Si el campo se deja vacío no se modificará la contraseña. )
                </div>
            </div>
            <div class="form-group">
                <label for="password2" class="col-sm-2 control-label">Confirma contraseña</label>
                <div class="col-sm-10">
                    <input id="password2" type="password" name="password2" onchange="comprobar_pass()">
                </div>
            </div>
            <div class="form-group">
                <label for="direccion_fisica" class="col-sm-2 control-label">Dirección física</label>
                <div class="col-sm-10">
                    <input id="direccion_fisica" type="text" size="45" name="direccion_fisica" value="{{ $user->direccion_fisica }}" onchange="comprobar()" required>
                </div>
            </div>
            <div class="form-group">
                <label for="telefono" class="col-sm-2 control-label">Teléfono</label>
                <div class="col-sm-10">
                    <input id="telefono" type="tel" pattern="[0-9]{9}" name="telefono" value="{{ $user->telefono }}" onchange="comprobar()" required>
                </div>
            </div>
            <div class="form-group">
                <label for="dni" class="col-sm-2 control-label">DNI</label>
                <div class="col-sm-10">
                    <input id="dni" type="text" name="dni" value="{{ $user->dni }}" onload="comprobar()" onchange="comprobar()" required>
                </div>
            </div>
            @if (!$user->hasRol("cliente") && $user->id != \Auth::user()->id)
                <div class="form-group">
                    <label for="rol" class="col-sm-2 control-label">Rol</label>
                    <div class="col-sm-10">
                        <select id="rol" name="rol" onload="comprobar_rol()" onchange="comprobar(), comprobar_rol()">
                            <option>Seleccione una opción</option>
                            <option value="1" @if($user->rol == 1) echo selected @endif>Comercial</option>
                            <option value="2" @if($user->rol == 2) echo selected @endif>Técnico</option>
                            <option value="3" @if($user->rol == 3) echo selected @endif>Director comercial</option>
                            <option value="4" @if($user->rol == 4) echo selected @endif>Administrador</option>
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-sm-offset-2 col-sm-10">
                <input id="boton" type="submit" value="Editar" class="btn btn-success" disabled>
            </div>
        </form>
    </div>
    <div class="col-lg-3">
        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading"><h4>Contraseña aleatoria </h4></div>
                <div class="panel-body">
                    <div class="input-group">
                                  <span class="input-group-btn">
                                    <button class="btn btn-primary" onclick="pass_aleatoria()" type="button">Crear</button>
                                  </span>
                        <input id="pass_aleatoria" type="text" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    alert('DNI erróneo');
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

            if ((password == password2) && comprobar_tamano()) {
                @if($user->hasRol("cliente") || $user->hasRol("administrador"))
                    document.getElementById('boton').disabled=false;
                @else
                    comprobar_rol();
                @endif
            }
            else{
                alert('La contraseña no coincide o es demasiado corta.');
                document.getElementById('boton').disabled=true;
            }
        }

        function pass_aleatoria() {
            var randomstring = Math.random().toString(36).slice(-10);
            console.log(randomstring);
            document.getElementById('pass_aleatoria').value=randomstring;
        }

    </script>

@endsection