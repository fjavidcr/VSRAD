@extends('layouts.app')

@section('content')
    <div class="container container-page">

        <div class="row">
            <div class="col-lg-12">


                @if(count($comerciales) == 0)
                    <div class="alert alert-warning">
                        <b> No tienes comerciales.</b>
                    </div>
                @endif
                <h4>Informe de todos los comerciales</h4>
                <a href="{{ route('director_comercial.informe_todos_comerciales') }}" type="button" class="btn btn-primary btn-sm">
                    Pedir informe
                </a>
                    <hr>
                    <h4>Informe de un cliente</h4>
                    <form class="form-inline" action="{{route('director_comercial.informe_cliente')}}" method="post">
                        {{csrf_field()}}
                        <div class="form-group">
                            <select name="id_comercial" required>
                                <option>Seleccionar un cliente</option>
                                @foreach($clientes as $u)
                                    <option value="{{$u->id}}">{{$u->getName()}}</option>
                                @endforeach
                            </select>
                        </div>
                        &nbsp;
                        <input type="submit" value="Pedir Informe" class="btn btn-success btn-sm">
                    </form>

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
                    </div>
                </div>
        </div>
        </div>

    </div>

@endsection