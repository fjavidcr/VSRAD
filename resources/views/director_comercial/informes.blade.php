@extends('layouts.app')

@section('content')
    <div class="container container-page">

        <div class="row">

            <div class="col-lg-12">
                <div class="page-header">
                    <h1>Informes</h1>
                </div>
                @if(count($comerciales) == 0)
                    <div class="alert alert-warning">
                        <b> No tienes comerciales.</b>
                    </div>
                @endif
                    <div class="row">
                    <div class="col-lg-4">
                        <h4>Informe de todos los comerciales</h4>
                        <a href="{{ route('director_comercial.informe_todos_comerciales') }}" type="button" class="btn btn-primary btn-sm">
                            Pedir informe
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <h4>Informe de todos los clientes</h4>
                        <a href="{{ route('director_comercial.informe_todos_clientes') }}" type="button" class="btn btn-primary btn-sm">
                            Pedir informe
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <h4>Informe de un cliente</h4>
                        <form class="form-inline" action="{{route('director_comercial.informe_cliente')}}" method="post">
                            {{csrf_field()}}
                            <div class="form-group">
                                <select id="id_cliente" name="id_cliente" onchange="comprobar_cliente()" onload="comprobar_cliente()" required>
                                    <option>Seleccionar un cliente</option>
                                    @foreach($clientes as $u)
                                        <option value="{{$u->id}}">{{$u->getName()}}</option>
                                    @endforeach
                                </select>
                            </div>
                            &nbsp;
                            <input id="boton_informe_cliente" type="submit" value="Pedir Informe" class="btn btn-success btn-sm" disabled>
                        </form>
                    </div>
                    </div>
                <hr>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Informes de {{$user->getName()}}</h3>
                    </div>
                    <div class="panel-body">
                        @if(count($errors))
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $e)
                                        <li>{{$e}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(count($informes) == 0)
                            <div class="alert alert-info">
                                Aún no tienes ningún informe.
                            </div>
                        @else
                                <table class="table table-responsive table-striped">

                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Fecha de creación</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <input type="hidden" value="{{ $cont = 0 }}">
                                    @foreach($informes as $i)
                                        <tr>
                                            <td>{{ ++$cont }}</td>
                                            <td>{{ $i->nombre }}</td>
                                            <td>{{ $i->fecha_creacion }}</td>
                                            <td><a href="{{ route('director_comercial.ver_informe', $i->id) }}" type="button" class="btn btn-primary btn-sm">
                                                    Ver informe
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

    </div>

    <script>

        function comprobar_cliente() {
            var value = document.getElementById('id_cliente').value;
            console.log(value);

            if (value > 0) {
                document.getElementById('boton_informe_cliente').disabled=false;
            }
            else
                document.getElementById('boton_informe_cliente').disabled=true;
        }

    </script>

@endsection