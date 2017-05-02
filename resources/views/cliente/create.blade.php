@extends('layouts.app')

@section('content')

    <div class="container container-page">
        <div class="row">
            <div class="col-lg-12">
                <h2>Crear nuevo proyecto</h2>
                <hr>
                @if(count($errors))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $e)
                                <li>{{$e}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="form-inline" action="{{ route('cliente.store') }}" method="post">

                    {{ csrf_field() }}
                    <input id="id_plano" type="hidden" name="id_plano" value="1" class="form-control" required>

                    <div class="form-group">
                        <label for="nombre">Nombre del proyecto</label>
                        <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" onchange="habilita()" required>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div>
                            <textarea id="configuracion" name="configuracion" class="form-control" required>{{ old('configuracion') }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-10">
                            <div style="width:100%; white-space:nowrap;">
                                <div class="col-lg-2">
                                    <h3>Productos</h3>
                                    <div>
                                      <div id="productos" style="width: 100px; height: 360px"></div>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div id="myDiagramDiv" class="canvas-plano canvas-casa-1" style="background-color: #f0f9f6; border:  solid  1px #d3e0e9;"></div>
                                </div>
                            </div>
                            <hr>
                            <div id="restricciones" hidden>
                                Restriccciones de los productos añadidos:
                                <pre id="res-text" style="height:250px"></pre>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Coste total aproximado</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="input-group-addon col-xs-2">€</div>
                                        <input id="coste" type="text" name="coste" size="6" readonly>
                                    <footer><h6>Precio sin IVA</h6></footer>
                                </div>
                            </div>
                            <hr>
                            <div class="btn-group-vertical" role="group">
                                <!---<a class="btn btn-danger boton-clear">Limpiar componentes</a>--->
                                <input id="boton-guardar-proyecto" type="submit" class="btn btn-success" value="Guardar proyecto" disabled>

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
                    /*fixedBounds: Rect(0,0,669,460),*/
                    /*initialContentAlignment: go.Spot.Center,  // center the content*/
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
                            var array = JSON.parse(myDiagram.model.toJson());
                            array = array.nodeDataArray;

                            document.getElementById("configuracion").textContent = JSON.stringify(myDiagram.model.toJson());
                            console.log(array);
                            var costeTotal = 0;

                            for(var i in array){
                                costeTotal += parseFloat(array[i].coste);
                                console.log("coste: " + array[i].coste);
                            }
                            costeTotal = parseFloat(costeTotal).toFixed(2);
                            document.getElementById("coste").setAttribute("value", costeTotal);
                            if(costeTotal > 0)
                                document.getElementById('restricciones').hidden=false;
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
                    locationSpot: go.Spot.Center ,
                    // provide a visual warning about dropping anything onto an "item"
                    mouseDragEnter: function (e, node) {
                        e.handled = true;
                        node.findObject("SHAPE").fill = "red";
                        highlightGroup(node.containingGroup, false);
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
                $$(go.Shape,
                    {
                        figure: "RoundedRectangle",
                        name: "SHAPE",
                        fill: "white",
                    },
                    new go.Binding("fill", "color"),
                    new go.Binding("desiredSize", "size", go.Size.parse).makeTwoWay(go.Size.stringify)),
                // with the textual key in the middle
                $$(go.TextBlock,
                    {alignment: go.Spot.Center, font: 'bold 12px sans-serif', margin: 3},
                    new go.Binding("text", "nombre"))
            );  // end Node

        function highlightGroup(grp, show) {
            if (!grp) return;
            if (show) {  // check that the drop may really happen into the Group
                var tool = grp.diagram.toolManager.draggingTool;
                var map = tool.draggedParts || tool.copiedParts;  // this is a Map
                if (grp.canAddMembers(map.toKeySet())) {
                    grp.isHighlighted = true;
                    return;
                }
            }
            grp.isHighlighted = false;
        }

        var groupFill = "rgba(128,128,128,0.2)";
        var groupStroke = "gray";
        var dropFill = "rgba(128,255,255,0.2)";
        var dropStroke = "red";

        myDiagram.groupTemplate =
            $$(go.Group,
                {
                    layerName: "Background",
                    resizable: false, resizeObjectName: "SHAPE",
                    // because the gridSnapCellSpot is Center, offset the Group's location
                    locationSpot: new go.Spot(0, 0, CellSize.width/2, CellSize.height/2)
                },
                // always save/load the point that is the top-left corner of the node, not the location
                new go.Binding("position", "pos", go.Point.parse).makeTwoWay(go.Point.stringify),
                { // what to do when a drag-over or a drag-drop occurs on a Group
                    mouseDragEnter: function(e, grp, prev) { highlightGroup(grp, true); },
                    mouseDragLeave: function(e, grp, next) { highlightGroup(grp, false); },
                    mouseDrop: function(e, grp) {
                        var ok = grp.addMembers(grp.diagram.selection, true);
                        if (!ok) grp.diagram.currentTool.doCancel();
                    }
                },
                $$(go.Shape, "Rectangle",  // the rectangular shape around the members
                    { name: "SHAPE",
                        fill: groupFill,
                        stroke: groupStroke,
                        minSize: new go.Size(CellSize.width*2, CellSize.height*2)
                    },
                    new go.Binding("desiredSize", "size", go.Size.parse).makeTwoWay(go.Size.stringify),
                    new go.Binding("fill", "isHighlighted", function(h) { return h ? dropFill : groupFill; }).ofObject(),
                    new go.Binding("stroke", "isHighlighted", function(h) { return h ? dropStroke: groupStroke; }).ofObject())
            );

        // decide what kinds of Parts can be added to a Group
        myDiagram.commandHandler.memberValidation = function(grp, node) {
            if (grp instanceof go.Group && node instanceof go.Group) return false;  // cannot add Groups to Groups
            // but dropping a Group onto the background is always OK
            return true;
        };

        // what to do when a drag-drop occurs in the Diagram's background
        myDiagram.mouseDragOver = function(e) {
            if (!AllowTopLevel) {
                // but OK to drop a group anywhere
                if (!e.diagram.selection.all(function(p) { return p instanceof go.Group; })) {
                    e.diagram.currentCursor = "not-allowed";
                }
            }
        };

        myDiagram.mouseDrop = function(e) {
            if (AllowTopLevel) {
                // when the selection is dropped in the diagram's background,
                // make sure the selected Parts no longer belong to any Group
                if (!e.diagram.commandHandler.addTopLevelParts(e.diagram.selection, true)) {
                    e.diagram.currentTool.doCancel();
                }
            } else {
                // disallow dropping any regular nodes onto the background, but allow dropping "racks"
                if (!e.diagram.selection.all(function(p) { return p instanceof go.Group; })) {
                    e.diagram.currentTool.doCancel();
                }
            }
        };


        // start off with four "racks" that are positioned next to each other
        myDiagram.model = new go.GraphLinksModel([{ key: "G", isGroup: true, coste: 0, pos: "0 0", size: "736 587" }]);
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
                @foreach($pros as $p)
            { id: "{{$p->id}}", nombre:"{{$p->nombre}}", color: green, coste:{{$p->coste}}},
            @endforeach
        ]);

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
            document.getElementById("id_plano").value = casa_actual;
            console.log("Plano actual: " + casa_actual);

        });

        function habilita() {
            if(isNaN(document.getElementById('nombre').value))
                document.getElementById('boton-guardar-proyecto').disabled=false;
            else
                document.getElementById('boton-guardar-proyecto').disabled=true;
        }

    </script>

@endsection