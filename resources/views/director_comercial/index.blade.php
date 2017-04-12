@extends('layouts.app')

@section('content')
    <div class="container">
        <h3> Comerciales de {{$user->name}}</h3>
        <div class="row">
            <div class="col-lg-12">

                @if(count($comerciales) == 0)
                    <div class="alert alert-warning">
                        <b> No tienes comerciales.</b>
                    </div>
                @else

                    <table class="table table-responsive table-condensed ">
                        <thead>
                        <tr>
                            <th>ID </th>
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

@endsection