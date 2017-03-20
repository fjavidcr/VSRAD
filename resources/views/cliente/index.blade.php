@extends('layouts.app')

<!--- TODO: CAMBIAR TODO --->

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
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td>
                                    @if($p->getEstado() != "no_pendiente")
                                        <p class="labelValidado-{{$p->id}}">{{ $p->getTituloEstado() }}</p>
                                    @else
                                        <a data-id="{{$p->id}}" class="cambiar_estado btn btn-sm btn-primary">Pedir validación</a>
                                    @endif
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