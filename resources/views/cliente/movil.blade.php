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

    <div class="container container-page">
        <div class="row">
            <h3>Proyectos de {{ Auth::user()->getName() }}</h3>
            <a id="boton-guardar-proyecto" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_mensajes">
                    Mensajes
            </a>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12">
                @if(count($proyectos) == 0)
                    <div class="alert alert-warning">
                         Aún no tienes proyectos. <b>¡Crea tu primer proyecto!</b>
                    </div>
                @else
                    <table class="table table-responsive table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                        <input type="hidden" value="{{ $cont = 0 }}">
                        @foreach($proyectos as $p)
                            <tr>
                                <td>{{ ++$cont }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td><p class="labelValidado-{{$p->id}}">{{ $p->getTituloEstado() }}</p></td>
                                <td>
                                    <a class="btn btn-default btn-sm"
                                       href="{{ route('cliente.show', $p->id) }}">
                                        <span class="" aria-hidden="true"></span>
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
                            <th>Proyecto</th>
                            <th>Fecha de edición</th>
                            <th></th>
                        </tr>
                        </thead>
                        @foreach($proyectos as $p)
                            <tr>
                                <td>{{ $p->nombre }}</td>
                                <td>{{ $p->fecha_creacion }}</td>
                                <td><a href="{{ route('mensajes_movil', $p->id) }}"> Seleccionar</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready( function() {
            var height = $(window).height();
            var width = $(window).width();
            $.ajax({
                url: 'body.php',
                type: 'post',
                data: { 'width' : width, 'height' : height, 'recordSize' : 'true' },
                success: function(response) {
                    $("body").html(response);
                }
            });
        });
    </script>
</div>

</body>

</html>