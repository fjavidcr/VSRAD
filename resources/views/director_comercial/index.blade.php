@extends('layouts.app')

@section('content')
    <div class="container">
        <h3> Comerciales de {{$user->name}}</h3>
        <div class="row">
            <div class="col-lg-12">

                @if(count($comerciales) == 0)
                    <div class="alert alert-warning">
                        <b> No tienes comerciales.</b>
                    </div>
                @else

                    <table class="table table-responsive table-condensed ">
                        @foreach($comerciales as $c)
                            <thead>
                            <tr>
                                <th>ID de comercial: {{ $c->id }}</th>
                                <th>Nombre: {{ $c->name }}</th>
                                <th>Estado </th>
                                <th>Ténico </th>
                                <th>Oferta </th>
                            </tr>
                            </thead>


                        @endforeach
                    </table>
                @endif

            </div>
        </div>
    </div>

@endsection