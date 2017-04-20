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
                        <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" class="form-control"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="configuracion">Configuración</label>
                        <textarea id="configuracion" name="configuracion" class="form-control"
                                  required>{{ old('configuracion') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-lg-9 col-md-9">
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
                        <div class="col-lg-3 col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Herramientas
                                </div>
                                <div class="panel-body">
                                    <div class="btn-group-vertical" role="group">
                                        <!---<a class="btn btn-danger boton-clear">Limpiar componentes</a>--->
                                        <a class="btn btn-default boton-cambiar-plano">Cambiar plano</a>
                                        <!---<a class="btn btn-default">Limpiar</a>--->
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


        var AllowTopLevel = false;
        var CellSize = new go.Size(50, 50);

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
                    "draggingTool.isGridSnapEnabled": true,
                    "draggingTool.gridSnapCellSpot": go.Spot.Center,
                    "resizingTool.isGridSnapEnabled": true,
                    allowDrop: true,  // handle drag-and-drop from the Palette
                    // For this sample, automatically show the state of the diagram's model on the page
                    "ModelChanged": function (e) {
                        if (e.isTransactionFinished) {
                            document.getElementById("savedModel").textContent = myDiagram.model.toJson();
                        }
                    },
                    "animationManager.isEnabled": false,
                    "undoManager.isEnabled": true // enable Ctrl-Z to undo and Ctrl-Y to redo
                });

        // Regular Nodes represent items to be put onto racks.
        // Nodes are currently resizable, but if that is not desired, just set resizable to false.
        myDiagram.nodeTemplate =
            $$(go.Node, "Auto",
                {
                    resizable: true, resizeObjectName: "SHAPE",
                    // because the gridSnapCellSpot is Center, offset the Node's location
                    locationSpot: new go.Spot(0, 0, CellSize.width / 2, CellSize.height / 2),
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
                    {alignment: go.Spot.Center, font: 'bold 16px sans-serif'},
                    new go.Binding("text", "key"))
            );  // end Node

        // Groups represent racks where items (Nodes) can be placed.
        // Currently they are movable and resizable, but you can change that
        // if you want the racks to remain "fixed".
        // Groups provide feedback when the user drags nodes onto them.

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
                    resizable: true, resizeObjectName: "SHAPE",
                    // because the gridSnapCellSpot is Center, offset the Group's location
                    locationSpot: new go.Spot(0, 0, CellSize.width / 2, CellSize.height / 2)
                },
                // always save/load the point that is the top-left corner of the node, not the location
                new go.Binding("position", "pos", go.Point.parse).makeTwoWay(go.Point.stringify),
                { // what to do when a drag-over or a drag-drop occurs on a Group
                    mouseDragEnter: function (e, grp, prev) {
                        highlightGroup(grp, true);
                    },
                    mouseDragLeave: function (e, grp, next) {
                        highlightGroup(grp, false);
                    },
                    mouseDrop: function (e, grp) {
                        var ok = grp.addMembers(grp.diagram.selection, true);
                        if (!ok) grp.diagram.currentTool.doCancel();
                    }
                },
                $$(go.Shape, "Rectangle",  // the rectangular shape around the members
                    {
                        name: "SHAPE",
                        fill: groupFill,
                        stroke: groupStroke,
                        minSize: new go.Size(CellSize.width * 2, CellSize.height * 2)
                    },
                    new go.Binding("desiredSize", "size", go.Size.parse).makeTwoWay(go.Size.stringify),
                    new go.Binding("fill", "isHighlighted", function (h) {
                        return h ? dropFill : groupFill;
                    }).ofObject(),
                    new go.Binding("stroke", "isHighlighted", function (h) {
                        return h ? dropStroke : groupStroke;
                    }).ofObject())
            );

        // decide what kinds of Parts can be added to a Group
        myDiagram.commandHandler.memberValidation = function (grp, node) {
            if (grp instanceof go.Group && node instanceof go.Group) return false;  // cannot add Groups to Groups
            // but dropping a Group onto the background is always OK
            return true;
        };

        // what to do when a drag-drop occurs in the Diagram's background
        myDiagram.mouseDragOver = function (e) {
            if (!AllowTopLevel) {
                // but OK to drop a group anywhere
                if (!e.diagram.selection.all(function (p) {
                        return p instanceof go.Group;
                    })) {
                    e.diagram.currentCursor = "not-allowed";
                }
            }
        };

        myDiagram.mouseDrop = function (e) {
            if (AllowTopLevel) {
                // when the selection is dropped in the diagram's background,
                // make sure the selected Parts no longer belong to any Group
                if (!e.diagram.commandHandler.addTopLevelParts(e.diagram.selection, true)) {
                    e.diagram.currentTool.doCancel();
                }
            } else {
                // disallow dropping any regular nodes onto the background, but allow dropping "racks"
                if (!e.diagram.selection.all(function (p) {
                        return p instanceof go.Group;
                    })) {
                    e.diagram.currentTool.doCancel();
                }
            }
        };
        // start off with four "racks" that are positioned next to each other
        myDiagram.model = new go.GraphLinksModel([
            {key: "G1", isGroup: true, pos: "0 0", size: "200 200"},
            {key: "G2", isGroup: true, pos: "200 0", size: "200 200"},
            {key: "G3", isGroup: true, pos: "0 200", size: "200 200"},
            {key: "G4", isGroup: true, pos: "200 200", size: "200 200"}
        ]);
        // this sample does not make use of any links

        // initialize the first Palette
        productos =
            $$(go.Palette, "productos",
                { // share the templates with the main Diagram
                    nodeTemplate: myDiagram.nodeTemplate,
                    groupTemplate: myDiagram.groupTemplate,
                    layout: $$(go.GridLayout)
                });

        var green = '#B2FF59';
        var blue = '#81D4FA';
        var yellow = '#FFEB3B';

        // specify the contents of the Palette
        productos.model = new go.GraphLinksModel([
            {key: "g", color: green},
            {key: "b", color: blue},
            {key: "y", color: yellow}
        ]);

        jQuery(".boton-clear").click(function() {

            var confirmBox = confirm("Seguro que quieres borrar todo?");
            if (confirmBox == true)
                myDiagram.clear();
        });

        var casa_actual = 1;
        jQuery(".boton-cambiar-plano").click(function() {
            jQuery("#myDiagramDiv").removeClass("canvas-casa-" + casa_actual);
            casa_actual++;
            jQuery("#myDiagramDiv").addClass("canvas-casa-" + casa_actual);
        });


    </script>

@endsection