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
                        <input type="hidden" value="{{ $cont = 0 }}">
                        @foreach($clientes as $c)
                            <thead>
                            <tr class="success">
                                <th># {{ ++$cont }}  {{ $c->name }}</th>
                                <th>Nombre</th>
                                <th>Estado </th>
                                <th>Ténico </th>
                                <th>Oferta </th>
                            </tr>
                            </thead>
                            <input type="hidden" value="{{ $cont2 = 0 }}">
                                @foreach($c->proyectos as $p)
                                    <tr>
                                        <td># {{ $cont }}.{{ ++$cont2 }}</td>
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
                                                                <option value="{{$t->id}}">{{$t->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="id_proyecto" value="{{$p->id}}">
                                                        <button type="submit" class="btn btn-success btn-xs">Asignar</button>
                                                    </div>
                                                </form>
                                            @else
                                                {{$p->getTecnico()->name}}
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
                                @endforeach
                        @endforeach
                    </table>
                @endif

            </div>
        </div>
    </div>

@endsection