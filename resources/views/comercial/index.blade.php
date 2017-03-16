@extends('layouts.app')

@section('content')
    <div class="container">
        <h3> Clientes de {{$user->name}}</h3>
        <div class="row">
            <div class="col-lg-12">

                @if(count($clientes) == 0)
                    <div class="alert alert-warning">
                        <b>Ups!</b> No tienes clientes asignados.
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
                        @foreach($users as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td>
                                    @if($p->validado)
                                        <p class="labelValidado-{{$p->id}}">Validado</p>
                                    @else
                                        <p class="labelValidado-{{$p->id}}">Pendiente de validación</p>
                                    @endif
                                    <a data-id="{{$p->id}}" class="cambiarEstado btn btn-sm btn-primary">Cambiar
                                        estado</a>
                                </td>
                                <td>
                                    <a class="btn btn-default btn-xs"
                                       href="{{ route('proyectos.show', $p->id) }}">
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