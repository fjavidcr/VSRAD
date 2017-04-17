@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="panel panel-default">
        <div class="panel-heading"><h4><b> Comerciales de {{$user->name}}</b></h4></div>
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
                            <th>Nombre </th>
                            <th>Estado </th>
                            <th>Oferta máxima </th>
                        </tr>
                        </thead>
                        @foreach($comerciales as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->name .' '. $c->apellidos }}</td>
                                <td> @if($c->oculto == 0) Habilitado @else Deshabilitado @endif </td>
                                <td>
                                    <form action="{{route('director_comercial.asignar_oferta')}}" method="post">
                                        {{csrf_field()}}
                                        <div class="form-inline">
                                            <input id="oferta" type="number" min="0" max="99.99" step="0.01" value="{{$c->oferta}}" name="oferta"> %
                                            <input type="hidden" name="id" value="{{$c->id}}">
                                            <button type="submit" class="btn btn-success btn-xs">Asignar</button>
                                        </div>
                                    </form>
                                </td>
                                <td><a class="btn btn-primary btn-xs"
                                        href="">
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
                    </div>
                </div>
                <div class="form-group">
                    <label for="rol" class="col-sm-2 control-label">Comercial</label>
                    <div class="col-sm-10">
                        <select name="id_comercial" required>
                            <option>Seleccionar un comercial</option>
                            @foreach($comerciales as $u)
                                <option value="{{$u->id}}">{{$u->name .' '. $u->apellidos}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Añadir" class="btn btn-success">
                </div>
            </form>
        </div>
        </div>

    </div>

@endsection