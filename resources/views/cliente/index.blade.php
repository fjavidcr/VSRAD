@extends('layouts.app')

<!--- TODO: CAMBIAR TODO --->

@section('content')

    <div class="container container-page">
        <div class="row">
            <div class="col-lg-12">
                <h3>Proyectos de {{ $user->name }}</h3>
                    <div  class="btn-group" role="group">
                    <a href="{{ route('cliente.create') }}" type="button" class="btn btn-primary">
                        Nuevo proyecto
                    </a>

                    <a id="boton-guardar-proyecto" type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_mensajes">
                            Mensajes
                    </a>
                    </div>
                </ul>
            </div>
        </div>

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
                            <th>Fecha de edición</th>
                            <th>Coste total (sin IVA)</th>
                            <th>Promoción</th>
                            <th>Estado</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <input type="hidden" value="{{ $cont = 0 }}">
                        @foreach($proyectos as $p)
                            <tr>
                                <td>{{ ++$cont }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td>{{ $p->fecha_creacion }}</td>
                                <td>{{ $p->coste }} €</td>
                                <td>{{ $p->oferta }} % descuento</td>
                                <td>
                                    @if($p->getEstado() != "no_pendiente")
                                        <p class="labelValidado-{{$p->id}}">{{ $p->getTituloEstado() }}</p>
                                    @else
                                        @if($user->isRegistered())
                                            <a href="{{ route('cliente.cambiar_estado', $p->id) }}" data-id="{{$p->id}}" class="cambiar_estado btn btn-sm btn-primary">Pedir validación</a>
                                        @else
                                            <button id="boton-guardar-proyecto" type="button" class="cambiar_estado btn btn-xs btn-primary" data-toggle="modal" data-target="#myModal">
                                                Pedir validación
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Completa tu registro para continuar</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form name="formulario" class="form-horizontal" action="{{ route('cliente.completar_registro') }}" method="post">

                                                                {{ csrf_field() }}
                                                                <input type="hidden" id="id_proyecto" name="id_proyecto" value="{{$p->id}}">
                                                                <div class="form-group">
                                                                    <label for="nombre" class=" col-sm-3 control-label">Nombre</label>
                                                                    <div class="col-sm-9">
                                                                        <input id="name" type="text" name="name" value="{{ $user->name }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="apellidos" class=" col-sm-3 control-label">Apellidos</label>
                                                                    <div class="col-sm-9">
                                                                        <input id="apellidos" type="text" name="apellidos" value="{{ $user->apellidos }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="email" class="col-sm-3  control-label">Email</label>
                                                                    <div class="col-sm-9">
                                                                        <input id="email" type="text" name="email" value="{{ $user->email }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="direccion_fisica" class="col-sm-3  control-label">Dirección física</label>
                                                                    <div class="col-sm-9">
                                                                        <input id="direccion_fisica" type="text" name="direccion_fisica" value="{{ $user->direccion_fisica }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="dni" class="col-sm-3  control-label">DNI</label>
                                                                    <div class="col-sm-9">
                                                                        <input id="dni" type="text" name="dni" value="{{ $user->dni }}" onchange="comprobar_DNI()" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="telefono" class="col-sm-3  control-label">Teléfono</label>
                                                                    <div class="col-sm-9">
                                                                        <input id="telefono" type="tel" name="telefono" value="{{ $user->telefono }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-sm-offset-2 col-sm-10">
                                                                        <button id="enviar" type="submit" class="btn btn-success" disabled>Enviar</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($p->getEstado() == "no_pendiente")
                                        <a class="btn btn-default btn-sm"
                                           href="{{ route('cliente.edit', $p->id) }}">
                                            Editar
                                        </a>
                                    @else
                                        <a class="btn btn-default btn-sm"
                                           href="{{ route('cliente.show', $p->id) }}">
                                            <span class="" aria-hidden="true"></span>
                                            Ver
                                        </a>
                                    @endif
                                </td>
                                    @if($p->puedeEliminar())
                                        <td>
                                        <form class="form-inline" action="{{ route('cliente.destroy') }}" method="post">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <input type="hidden" name="id" value="{{$p->id}}">
                                                <input type="hidden" name="_method" value="delete">
                                                <input type="submit" class="btn btn-danger btn-sm" value="Eliminar">
                                            </div>
                                        </form>
                                        </td>
                                    @elseif($p->getEstado() == "validado")
                                        <td>
                                        <form class="form-inline" action="{{ route('cliente.comprar') }}" method="post">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <input type="hidden" name="id" value="{{$p->id}}">
                                                <input type="submit" class="btn btn-success btn-sm" value="Comprar">
                                            </div>
                                        </form>
                                        </td>
                                        <td>
                                        <form class="form-inline" action="{{ route('cliente.rechazar') }}" method="post">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <input type="hidden" name="id" value="{{$p->id}}">
                                                <input type="submit" class="btn btn-danger btn-sm" value="Rechazar">
                                            </div>
                                        </form>
                                        </td>
                                    @elseif($p->getEstado() == "pendiente")
                                        <td>Esperando validación...</td>
                                    @else
                                        <td></td>
                                    @endif
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
                        </tr>
                        </thead>
                        @foreach($proyectos as $p)
                            <tr>
                                <td>{{ $p->nombre }}</td>
                                <td>{{ $p->fecha_creacion }}</td>
                                <td><a href="{{ route('cliente.mensajes', $p->id) }}"> Seleccionar</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function comprobar_DNI() {
            var numero
            var letr
            var letra
            var dni = document.getElementById('dni').value;
            dni = dni.toUpperCase();
            console.log(dni);

            numero = dni.substr(0,dni.length-1);
            letr = dni.substr(dni.length-1,1);
            numero = numero % 23;
            letra='TRWAGMYFPDXBNJZSQVHLCKET';
            letra=letra.substring(numero,numero+1);
            if (letra!=letr.toUpperCase()) {
                document.getElementById('enviar').disabled=true;
                alert('DNI erroneo');
            }
            else{
                document.getElementById('enviar').disabled=false;
            }
        }

    </script>
@endsection