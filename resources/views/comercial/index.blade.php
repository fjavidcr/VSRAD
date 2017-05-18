@extends('layouts.app')

@section('content')
    <div class="container container-page">
        <h3> Clientes de {{ Auth::user()->getName() }}</h3>
        <div class="row">
            <div class="col-lg-12">

                @if(count($clientes) == 0)
                    <div class="alert alert-warning">
                        <b> No tienes clientes asignados.</b>
                    </div>
                @else
                    <a id="boton-guardar-proyecto" type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_mensajes">
                        Mensajes
                    </a>
                    @foreach($clientes as $c)
                        <table class="table table-responsive table-condensed table-striped">

                            <input type="hidden" value="{{ $existe = 0 }}">
                            @foreach($c->proyectos as $p)
                                @if($p->oculto == 0)
                                    <input type="hidden" value="{{ $existe = 1 }}">
                                @endif
                            @endforeach

                            @if($existe == 0)
                                <div class="alert alert-info">
                                    <b> No existen proyectos del cliente {{ $c->getName() }}.</b>
                                </div>
                            @else
                                <caption><h4>{{ $c->getName() }}</h4></caption>
                                <thead>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Estado </th>
                                    <th>Ténico </th>
                                    <th>Oferta </th>
                                </thead>
                                <input type="hidden" value="{{ $cont = 0 }}">
                                @foreach($c->proyectos as $p)
                                    @if($p->oculto == 0)
                                        <tr @if($p->getEstado() == "comprado")
                                                class="success"
                                            @elseif($p->getEstado() == "rechazado")
                                                class="danger"
                                                @endif>
                                            <td>{{ ++$cont }}</td>
                                            <td>{{ $p->nombre }}</td>
                                            <td>{{ $p->getTituloEstado() }}</td>
                                            <td>
                                                @if(!isset($p->id_tecnico))
                                                    <form action="{{route('comercial.asignar_tecnico')}}" method="post">
                                                        {{csrf_field()}}
                                                        <div class="form-inline">
                                                            <select name="id_tecnico">
                                                                <option>Seleccionar un técnico</option>
                                                                @foreach($tecnicos as $t)
                                                                    <option value="{{$t->id}}">{{$t->getName()}}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" name="id_proyecto" value="{{$p->id}}">
                                                            <button type="submit" class="btn btn-success btn-xs">Asignar</button>
                                                        </div>
                                                    </form>
                                                @else
                                                    {{$p->getTecnico()->getName()}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($p->oferta == 0 && $p->getEstado() == "validado" )
                                                    <form action="{{route('comercial.asignar_oferta')}}" method="post">
                                                        {{csrf_field()}}
                                                        <div class="form-inline">
                                                            <input id="oferta" type="number" min="0" max="{{$user->oferta}}" step="0.01" value="0.00" name="oferta"> %
                                                            <input type="hidden" name="id_proyecto" value="{{$p->id}}">
                                                            <button type="submit" class="btn btn-success btn-xs">Asignar</button>
                                                        </div>
                                                    </form>
                                                @else
                                                    {{$p->oferta}} %
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </table>
                    @endforeach

                @endif

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_mensajes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Selecciona un proyecto</h4>
                </div>
                <div class="modal-body">
                    <table class="table able table-responsive table-striped">
                        <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Proyecto</th>
                            <th>Fecha de edición</th>
                        </tr>
                        </thead>
                        @foreach($clientes as $c)
                            @foreach($c->proyectos as $p)
                                @if($p->oculto == 0)
                                    <tr>
                                        <td>{{ $c->getName() }}</td>
                                        <td>{{ $p->nombre }}</td>
                                        <td>{{ $p->fecha_creacion }}</td>
                                        <td><a href="{{ route('comercial.mensajes', $p->id) }}"> Seleccionar</a></td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection