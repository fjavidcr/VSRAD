@extends('layouts.app')

@section('content')
    <div class="container">
        <h3> Clientes de {{$user->name}}</h3>
        <div class="row">
            <div class="col-lg-12">

                @if(count($clientes) == 0)
                    <div class="alert alert-warning">
                        <b> No tienes clientes asignados.</b>
                    </div>
                @else

                    <table class="table table-responsive table-striped">

                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                        @foreach($clientes as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->name }}</td>
                                <td>
                                    @if($c->validado)
                                        <p class="labelValidado-{{$c->id}}">Validado</p>
                                    @else
                                        <p class="labelValidado-{{$c->id}}">Pendiente de validación</p>
                                    @endif
                                    <a data-id="{{$c->id}}" class="cambiarEstado btn btn-sm btn-primary">Cambiar
                                        estado</a>
                                </td>
                                <td>
                                    <a class="btn btn-default btn-xs"
                                       href="{{ route('proyectos.show', $c->id) }}">
                                        Ver
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