@extends('layouts.app')

@section('content')

    <div class="container container-page">
        <div class="row">
            <div class="col-lg-12">
                <h3>Proyectos asignados a  {{ $user->name }}</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @if(count($proyectos) == 0)
                    <div class="alert alert-success">
                        No tienes proyectos asignados.
                    </div>
                @else
                    <a id="boton-guardar-proyecto" type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_mensajes">
                        Mensajes
                    </a>
                    <table class="table table-responsive table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Fecha de edición</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <input type="hidden" value="{{ $cont = 0 }}">
                        @foreach($proyectos as $p)
                            <tr>
                                <td>{{ ++$cont }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td>{{ $p->fecha_creacion }}</td>
                                <td>{{ $p->getCliente()->name }}</td>
                                <td>{{ $p->getTituloEstado() }}</td>
                                @if($p->getEstado() == "pendiente")
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('tecnico.proyecto', $p->id) }}">
                                            Revisar
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a class="btn btn-success btn-sm"
                                           href="{{ route('tecnico.show', $p->id) }}">
                                            Ver
                                        </a>
                                    </td>
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
                                <td><a href="{{ route('tecnico.mensajes', $p->id) }}"> Seleccionar</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(".cambiarEstado").click(function () {

            var id = $(this).data('id');

            $.ajax({
                url: "http://vsrad.dev/proyectos/cambiarEstado/" + id,
                context: document.body,
                method: "get",
                success: todoOk,
                error: todoMal
            });

        });

        function todoOk(data) {

            var labelvalidado = $(".labelValidado-" + data.id);

            labelvalidado.toggleClass("btn-primary btn-success");

            labelvalidado.text("Pendiente de validación");

            if (data.validado)
                labelvalidado.text("Validado");
        }

        function todoMal() {
            alert("Todo mal");
        }

    </script>

@endsection