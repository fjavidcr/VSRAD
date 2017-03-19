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

                    <table class="table table-responsive table-bordered ">
                        @foreach($clientes as $c)
                            <thead>
                            <tr class="success">
                                <th>ID de cliente: {{ $c->id }}</th>
                                <th>Nombre: {{ $c->name }}</th>
                                <th>Oferta: {{$c->oferta}}</th>
                                <th><a class="btn btn-default btn-xs"
                                      href="{{ route('comercial.asignarOfertaTecnico', $c->id) }}">
                                        Editar asignación
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            @if(count($c->proyectos) == 0)
                                <tr>
                                    <div class="alert alert-warning">
                                        El cliente no tiene proyectos creados.
                                    </div>
                                </tr>
                            @else
                                @foreach($c->proyectos as $p)
                                    <tr>
                                        <td>ID de Proyecto: {{ $p->id }}</td>
                                        <td>{{ $p->nombre }}</td>
                                        <td>
                                            @if($p->estado)
                                                <p class="labelValidado-{{$c->id}}">Validado</p>
                                            @else
                                                <p class="labelValidado-{{$c->id}}">Pendiente de validación</p>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-default btn-xs"
                                               href="{{ route('proyectos.show', $c->id) }}">
                                                Ver proyecto
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </table>
                @endif

            </div>
        </div>
    </div>

@endsection