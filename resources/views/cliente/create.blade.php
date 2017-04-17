@extends('layouts.app')

@section('content')

    <div class="container container-page">
        <div class="row">
            <div class="col-lg-12">
                <h3>Crear nuevo proyecto</h3>

                @if(count($errors))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $e)
                                <li>{{$e}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('cliente.store') }}" method="post">

                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="nombre">Nombre del proyecto</label>
                        <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="configuracion">Configuracion</label>
                        <textarea id="configuracion" name="configuracion" class="form-control" required>{{ old('configuracion') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-lg-9 col-md-9">
                            <div id="planoCasaDiagram" class="canvas-plano canvas-casa-1"></div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Herramientas
                                </div>
                                <div class="panel-body">
                                    <div class="btn-group-vertical" role="group">
                                        <a class="btn btn-danger boton-clear">Limpiar componentes</a>
                                        <a class="btn btn-default boton-cambiar-plano">Cambiar plano</a>
                                        <a class="btn btn-default">Limpiar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="submit" value="Crear" class="btn btn-success">

                </form>

            </div>
        </div>
    </div>

    <script>
        var $ = go.GraphObject.make;
        var myDiagram = $(go.Diagram, "planoCasaDiagram");
        var model = $(go.Model);

        myDiagram.nodeTemplate =
            $(go.Node, "Vertical",
                // the entire node will have a light-blue background
                {background: "#44CCFF"},
                $(go.Picture,
                    // Pictures should normally have an explicit width and height.
                    // This picture has a red background, only visible when there is no source set
                    // or when the image is partially transparent.
                    {margin: 10, width: 50, height: 50, background: "red"},
                    // Picture.source is data bound to the "source" attribute of the model data
                    new go.Binding("source")),
                $(go.TextBlock,
                    "Default Text",  // the initial value for TextBlock.text
                    // some room around the text, a larger font, and a white stroke:
                    {margin: 12, stroke: "white", font: "bold 16px sans-serif"},
                    // TextBlock.text is data bound to the "name" attribute of the model data
                    new go.Binding("text", "name"))
            );

        model.nodeDataArray =
            [ // note that each node data object holds whatever properties it needs;
                // for this app we add the "name" and "source" properties
                {name: "Humo", source: "http://lorempixel.com/100/100"},
                {name: "Puerta", source: "http://lorempixel.com/100/100"},
                {name: "Centralita", source: "http://lorempixel.com/100/100"}
            ];

        myDiagram.model = model;

        jQuery(".boton-clear").click(function() {

            var confirmBox = confirm("Seguro que quieres borrar todo?");
            if (confirmBox == true)
                myDiagram.clear();
        });

        var casa_actual = 1;
        jQuery(".boton-cambiar-plano").click(function() {
            jQuery("#planoCasaDiagram").removeClass("canvas-casa-" + casa_actual);
            casa_actual++;
            jQuery("#planoCasaDiagram").addClass("canvas-casa-" + casa_actual);
        });

    </script>

@endsection