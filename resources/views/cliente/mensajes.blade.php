@extends('layouts.app')

@section('content')


        @if(count($errors))
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{$e}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                @if(count($mensajes) == 0)
                    <div class="alert alert-warning">
                        <p>No se ha enviado ningún mensaje aún.</p>
                    </div>
                @endif
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Mensajes
                    </div>
                    <div class="panel-body panel-mensajes">
                        <ul class="chat">
                            @foreach($mensajes as $m)
                                @if($m->remitente == 0)
                                    <li class="left clearfix">
                                        <div class="chat-body clearfix">
                                            <div class="header">
                                                <strong class="primary-font">Cliente</strong>
                                                <small class="pull-right text-muted">{{$m->fecha_creacion}}</small>
                                            </div>
                                            <p>{{$m->texto}}</p>
                                        </div>
                                    </li>
                                @elseif($m->remitente == 1)
                                    <li class="right clearfix">
                                        <div class="chat-body clearfix">
                                            <div class="header">
                                                <small class=" text-muted">{{$m->fecha_creacion}}</small>
                                                <strong class="pull-right primary-font">Técnico</strong>
                                            </div>
                                            <p>{{$m->texto}}</p>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="panel-footer">
                        <form action="{{ route('cliente.enviar_mensaje') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_proyecto" value="{{$proyecto->id}}">
                            <div class="input-group">
                                <input id="btn-input" name="texto" type="text" class="form-control input-sm" placeholder="Escribe aquí tu mensaje..." value="{{ old('texto') }}"/>
                                <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-sm" id="btn-chat">
                                Enviar</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-lg-1"></div>
        </div>

@endsection