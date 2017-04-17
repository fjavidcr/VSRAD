@extends('layouts.app')

@section('content')
    <h3>Crear usuario</h3>
    <form class="form-horizontal" action="{{route('administrador.crear_usuario')}}" method="post">

        {{csrf_field()}}
        <div class="form-group">
            <label for="nombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input id="name" type="text" name="name"required>
            </div>
        </div>
        <div class="form-group">
            <label for="apellidos" class="col-sm-2 control-label">Apellidos</label>
            <div class="col-sm-10">
                <input id="apellidos" type="text" name="apellidos"required>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input id="email" type="text" name="email"required>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">Contraseña</label>
            <div class="col-sm-10">
                <input id="password" type="password" name="password"required>
            </div>
        </div>
        <div class="form-group">
            <label for="direccion_fisica" class="col-sm-2 control-label">Dirección física</label>
            <div class="col-sm-10">
                <input id="direccion_fisica" type="text" name="direccion_fisica"required>
            </div>
        </div>
        <div class="form-group">
            <label for="telefono" class="col-sm-2 control-label">Teléfono</label>
            <div class="col-sm-10">
                <input id="telefono" type="text" name="telefono"required>
            </div>
        </div>
        <div class="form-group">
            <label for="dni" class="col-sm-2 control-label">DNI</label>
            <div class="col-sm-10">
                <input id="dni" type="text" name="dni" required>
            </div>
        </div>
        <div class="form-group">
            <label for="rol" class="col-sm-2 control-label">Rol</label>
            <div class="col-sm-10">
                <select name="rol"required>
                    <option>Seleccione una opción</option>
                    <option value="1">Comercial</option>
                    <option value="2">Técnico</option>
                    <option value="3">Director comercial</option>
                    <option value="4">Administrador</option>
                </select>
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" value="Crear" class="btn btn-success" onclick="comprobar()">
        </div>
    </form>

    <script>

        function comprobar() {
            var numero
            var letr
            var letra
            var expresion_regular_dni
            var dni = formulario.dni.value;

            expresion_regular_dni = /^\d{8}[a-zA-Z]$/;

            //if(expresion_regular_dni.test (dni) == true){
            numero = dni.substr(0,dni.length-1);
            letr = dni.substr(dni.length-1,1);
            numero = numero % 23;
            letra='TRWAGMYFPDXBNJZSQVHLCKET';
            letra=letra.substring(numero,numero+1);
            if (letra!=letr.toUpperCase()) {
                alert('DNI erroneo, la letra del DNI no se corresponde');
            }
            /*}else{
             alert('DNI erroneo, formato no válido');
             }*/
        }
    </script>
@endsection