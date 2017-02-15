@extends('layouts.app')

@section('content')
    <div class="container">
        <h3> Proyectos de {{$user->name}}</h3>

        <table class="table table-responsive">
            <tr>
                <th>ID</th>
                <th></th>
                <a href="{{route('proyectos.show', $p->id)}}"
            </tr>
        </table>

        <ul>
            <ul>
                @foreach($proyectos as $p)
                    <li>{{$p->nombre}}</li>
                @endforeach
            </ul>
        </ul>
        <a href="{{route('proyectos.create')}}" class="btn btn-primary">Nuevo proyecto</a>
    </div>