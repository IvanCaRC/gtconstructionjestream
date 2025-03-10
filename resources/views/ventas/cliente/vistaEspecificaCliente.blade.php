@extends('layouts.app')
@section('title', 'Vista especifica de cliente')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')

@section('activeGestionClientes', 'active')
@section('activeFondoPermanenteGestionCLientes', 'background-permanent')
@section('contend')
<link rel="stylesheet" href="{{ asset('css/crearClienteProyecto.css') }}">

    @livewire('cliente.vista-especifica', ['idCliente' => $idCliente])

@endsection
