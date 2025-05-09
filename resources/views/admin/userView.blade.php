@extends('layouts.app')

@section('title', 'Vista Usuario')
@section('contend')
    <br>
    @livewire('view-user', ['iduser' => $iduser])


    <div class="container my-5">
        @include('admin.datosCompras.datosComprados')
        @include('admin.datosCompras.graficasComprados')
    </div>
@endsection
