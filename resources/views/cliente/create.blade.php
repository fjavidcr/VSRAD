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
                    <input id="id_plano" type="hidden" name="id_plano" value="{{ old('id_plano') }}" class="form-control" required>

                    <div class="form-group">
                        <label for="nombre">Nombre del proyecto</label>
                        <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="configuracion">Configuración</label>
                        <div hidden>
                            <textarea id="configuracion" name="configuracion" class="form-control" required>{{ old('configuracion') }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-10 col-md-10">
                            <div style="width:100%; white-space:nowrap;">
                                <span style="display: inline-block; vertical-align: top">
                                    <h3>Productos</h3>
                                    <div>
                                      <div id="productos" style="width: 140px; height: 360px"></div>
                                    </div>
                                </span>
                                <span style="display: inline-block; vertical-align: top; width:80%">
                                    <div id="myDiagramDiv" class="canvas-plano canvas-casa-1" style="background-color: #e4f9e6; border:  solid  1px #d3e0e9;"></div>
                                </span>
                            </div>

                            <div>
                                Diagram Model saved in JSON format, automatically updated after each transaction:
                                <pre id="savedModel" style="height:250px"></pre>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <div class="btn-group-vertical" role="group">
                                <!---<a class="btn btn-danger boton-clear">Limpiar componentes</a>--->
                                <input type="submit" value="Guardar proyecto" class="btn btn-success">
                                <a class="btn btn-default boton-cambiar-plano">Cambiar plano</a>
                                <!---<a class="btn btn-default">Limpiar</a>--->
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>


        var AllowTopLevel = false;
        var CellSize = new go.Size(30, 30);

        var $$ = go.GraphObject.make;
        var myDiagram =
            $$(go.Diagram, "myDiagramDiv",
                {
                    grid: $$(go.Panel, "Grid",
                        {gridCellSize: CellSize},
                        $$(go.Shape, "LineH", {stroke: "lightgray"}),
                        $$(go.Shape, "LineV", {stroke: "lightgray"})
                    ),
                    // support grid snapping when dragging and when resizing
                    "draggingTool.isGridSnapEnabled": false,
                    "draggingTool.gridSnapCellSpot": go.Spot.Center,
                    "resizingTool.isGridSnapEnabled": false,
                    allowDrop: true,  // handle drag-and-drop from the Palette
                    // For this sample, automatically show the state of the diagram's model on the page
                    "ModelChanged": function (e) {
                        if (e.isTransactionFinished) {
                            document.getElementById("configuracion").textContent = myDiagram.model.toJson();
                            document.getElementById("savedModel").textContent = myDiagram.model.toJson();
                        }
                    },
                    "animationManager.isEnabled": true,
                    "undoManager.isEnabled": true // enable Ctrl-Z to undo and Ctrl-Y to redo
                });

        // Regular Nodes represent items to be put onto racks.
        // Nodes are currently resizable, but if that is not desired, just set resizable to false.
        myDiagram.nodeTemplate =
            $$(go.Node, "Auto",
                {
                    resizable: false, resizeObjectName: "SHAPE",
                    locationObjectName: "TB",
                    // because the gridSnapCellSpot is Center, offset the Node's location
                    locationSpot: new go.Spot(0, 0, CellSize.width / 2, CellSize.height / 2),
                    // provide a visual warning about dropping anything onto an "item"
                    mouseDragEnter: function (e, node) {
                        e.handled = true;
                        node.findObject("SHAPE").fill = "red";
                    },
                    mouseDragLeave: function (e, node) {
                        node.updateTargetBindings();
                    },
                    mouseDrop: function (e, node) {  // disallow dropping anything onto an "item"
                        node.diagram.currentTool.doCancel();
                    }
                },
                // always save/load the point that is the top-left corner of the node, not the location
                new go.Binding("position", "pos", go.Point.parse).makeTwoWay(go.Point.stringify),
                // this is the primary thing people see
                $$(go.Shape, "Rectangle",
                    {
                        name: "SHAPE",
                        fill: "white",
                        minSize: CellSize,
                        desiredSize: CellSize  // initially 1x1 cell
                    },
                    new go.Binding("fill", "color"),
                    new go.Binding("desiredSize", "size", go.Size.parse).makeTwoWay(go.Size.stringify)),
                // with the textual key in the middle
                $$(go.TextBlock,
                    {alignment: go.Spot.Center, font: 'bold 12px sans-serif'},
                    new go.Binding("text", "key"))
            );  // end Node


        var dropFill = "rgba(128,255,255,0.2)";
        var dropStroke = "red";

        // start off with four "racks" that are positioned next to each other
        myDiagram.model = new go.GraphLinksModel([]);
        // this sample does not make use of any links

        // initialize the Palette
        var productos =
            $$(go.Palette, "productos",
                { // share the templates with the main Diagram
                    nodeTemplate: myDiagram.nodeTemplate,
                    layout: $$(go.GridLayout)
                });

        var green = '#B2FF59';
        var blue = '#81D4FA';
        var yellow = '#FFEB3B';

        // specify the contents of the Palette
        productos.model = new go.GraphLinksModel([
            {key: "sensor", color: green},
            {key: "actuador", color: blue},
            {key: "central", color: yellow}
        ]);

        var prod = new Array();

        prod.push({key: "sensor", color: green});


        jQuery(".boton-clear").click(function() {

            var confirmBox = confirm("¿Seguro que quieres borrar todo?");
            if (confirmBox == true)
                myDiagram.clear();
        });

        var casa_actual = 1;
        jQuery(".boton-cambiar-plano").click(function() {
            jQuery("#myDiagramDiv").removeClass("canvas-casa-" + casa_actual);
            casa_actual++;
            if(casa_actual == 6){
                casa_actual = 1;
            }
            jQuery("#myDiagramDiv").addClass("canvas-casa-" + casa_actual);
            document.getElementById("id_plano").textContent = casa_actual;

        });


    </script>

@endsection