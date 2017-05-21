<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VSRAD') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/go-debug.js"></script>

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/movil') }}">
                    {{ config('app.name', 'VSRAD') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if (Auth::guest())
                        <li>  </li>
                    @elseif (Auth::user()->hasRol("cliente"))
                        <li><a href="{{ route('movil') }}">Mis proyectos</a></li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        {{--<li><a href="{{ route('register') }}">Registrar</a></li>--}}
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->getCompleteName() }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Salir
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>


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
                                                <strong class="primary-font">{{$m->nombre_cliente()}}</strong>
                                                <small class="pull-right text-muted">{{$m->fecha_creacion}}</small>
                                            </div>
                                            <p class="mensaje_cliente">{{$m->texto}}</p>
                                        </div>
                                    </li>
                                @elseif($m->remitente == 1)
                                    <li class="right clearfix">
                                        <div class="chat-body clearfix">
                                            <div class="header">
                                                <small class=" text-muted">{{$m->fecha_creacion}}</small>
                                                <strong class="pull-right primary-font">{{$m->nombre_tecnico()}}</strong>
                                            </div>
                                            <p class="mensaje_tecnico">{{$m->texto}}</p>
                                        </div>
                                    </li>
                                @elseif($m->remitente == 1)
                                    <li class="right clearfix">
                                        <div class="chat-body clearfix">
                                            <div class="header">
                                                <small class=" text-muted">{{$m->fecha_creacion}}</small>
                                                <strong class="pull-right primary-font">{{$m->nombre_comercial()}}</strong>
                                            </div>
                                            <p class="mensaje_comercial">{{$m->texto}}</p>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="panel-footer">
                        <form action="{{ route('enviar_mensaje_movil') }}" method="post">
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

</div>

</body>

</html>