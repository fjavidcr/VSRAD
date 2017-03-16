@extends('layouts.app')

@section('content')
    <div class="container">
        <h3> Clientes de {{$user->name}}</h3>

        <table class="table table-responsive">
            <tr>
                <th>ID</th>
                <th></th>
                <a href="{{route('clientes.show', $p->id)}}"></a>
            </tr>
        </table>

        <ul>
            <ul>
                @foreach($clintes as $c)
                    <li>{{$c->nombre}}</li>
                    @foreach($proyectos as $p)
                        <li>{{$p->nombre}}</li>

                    @endforeach
                @endforeach
            </ul>
        </ul>
    </div>