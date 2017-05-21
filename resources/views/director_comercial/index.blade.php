@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="panel panel-default">
        <div class="panel-heading"><h4><b> Comerciales </b></h4></div>
        <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">

                @if(count($comerciales) == 0)
                    <div class="alert alert-warning">
                        <b> No tienes comerciales.</b>
                    </div>
                @else

                    <table class="table table-responsive table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Oferta máxima</th>
                        </tr>
                        </thead>
                        <input type="hidden" value="{{ $cont = 0 }}">
                        @foreach($comerciales as $c)
                            <tr>
                                <td>{{ ++$cont }}</td>
                                <td>{{ $c->getName() }}</td>
                                <td> @if($c->oculto == 0) Habilitado @else Deshabilitado @endif </td>
                                <td>
                                    <form action="{{route('director_comercial.asignar_oferta')}}" method="post">
                                        {{csrf_field()}}
                                        <div class="form-inline">
                                            <input id="oferta" type="number" min="0" max="99.99" step="0.01" value="{{$c->oferta}}" name="oferta" required> %
                                            <input type="hidden" name="id" value="{{$c->id}}">
                                            <button type="submit" class="btn btn-success btn-xs">Asignar</button>
                                        </div>
                                    </form>
                                </td>
                                <td><a class="btn btn-primary btn-xs"
                                        href="{{route('director_comercial.informe_comercial', $c->id)}}">
                                        Pedir informe
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </table>
                @endif

            </div>
        </div>
        </div>
        </div>

        <div class="panel panel-default">
        <div class="panel-heading"><h4><b> Añadir nuevo cliente </b></h4></div>
        <div class="panel-body">
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

            <form class="form-horizontal" action="{{route('director_comercial.añadir_cliente')}}" method="post">

                {{csrf_field()}}
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Nombre</label>
                    <div class="col-sm-10">
                        <input id="name" type="text" name="name" required>
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
                    <label for="rol" class="col-sm-2 control-label">Comercial</label>
                    <div class="col-sm-10">
                        <select id="id_comercial" name="id_comercial" onchange="comprobar_boton()" onload="comprobar_boton()" required>
                            <option>Seleccionar un comercial</option>
                            @foreach($comerciales as $u)
                                <option value="{{$u->id}}">{{$u->name .' '. $u->apellidos}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <input id="boton_nuevo_cliente" type="submit" value="Añadir" class="btn btn-success"disabled>
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
        </div>

    </div>

    <script>

        function comprobar_boton() {
            var value = document.getElementById('id_comercial').value;
            console.log(value);

            if (value > 0) {
                document.getElementById('boton_nuevo_cliente').disabled=false;
            }
            else
                document.getElementById('boton_nuevo_cliente').disabled=true;
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
                comprobar_boton();
            }
            else{
                alert('La contraseña no coincide o es demasiado corta.');
                document.getElementById('boton_nuevo_cliente').disabled=true;
            }
        }

        function pass_aleatoria() {
            var randomstring = Math.random().toString(36).slice(-10);
            console.log(randomstring);
            document.getElementById('pass_aleatoria').value=randomstring;
        }

    </script>

@endsection