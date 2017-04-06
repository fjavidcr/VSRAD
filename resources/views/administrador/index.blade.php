@extends('layouts.app')

@section('content')
    <div class="container">
        <h3> Panel de control de {{$user->name}}</h3>
        <div class="row">
            <div class="col-lg-12">

                <h4> Usuarios </h4>
                <table class="table table-responsive table-bordered ">
                    <thead>
                        <th>ID de cliente</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                    </thead>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name . ' ' . $u->apellidos}}</td>
                            <td>{{ $u->email }} </td>
                            <td>{{$u->direccion_fisica}} </td>
                            <td>{{ $u->telefono }} </td>
                            <td>{{ $u->getTitle() }}</td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>

@endsection