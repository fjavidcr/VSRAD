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
                        No tienes proyectos que validar.
                    </div>
                @else
                    <table class="table table-responsive table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Proyecto</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <input type="hidden" value="{{ $cont = 0 }}">
                        @foreach($proyectos as $p)
                            <tr>
                                <td>{{ ++$cont }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td>{{ $p->getCliente()->name }}</td>
                                <td>{{ $p->getTituloEstado() }}</td>
                                @if($p->getEstado() == "pendiente")
                                    <td>
                                        <a class="btn btn-warning btn-xs"
                                           href="{{ route('tecnico.proyecto', $p->id) }}">
                                            Revisar
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