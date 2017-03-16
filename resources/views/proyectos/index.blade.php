@extends('layouts.app')

@section('content')

    <div class="container container-page">
        <div class="row">
            <div class="col-lg-12">
                <h3>Proyectos de {{ $user->name }}</h3>

                <a href="{{ route('proyectos.create') }}" class="btn btn-primary">
                    Nuevo proyecto
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                @if(count($proyectos) == 0)
                    <div class="alert alert-warning">
                        <b>Ups!</b> Parece que no tienes proyectos creados
                    </div>
                @else

                    <table class="table table-responsive table-striped">

                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Proyecto</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                        @foreach($proyectos as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td>
                                    @if($p->validado)
                                        <p class="labelValidado-{{$p->id}}">Validado</p>
                                    @else
                                        <p class="labelValidado-{{$p->id}}">Pendiente de validación</p>
                                    @endif
                                    <a data-id="{{$p->id}}" class="cambiarEstado btn btn-sm btn-primary">Cambiar
                                        estado</a>
                                </td>
                                <td>
                                    <a class="btn btn-default btn-xs"
                                       href="{{ route('proyectos.show', $p->id) }}">
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