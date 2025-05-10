@extends('layouts.app')
@section('title', 'Gestion de Clientes')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')

@section('activeGestionClientes', 'active')
@section('activeFondoPermanenteGestionCLientes', 'background-permanent')
@section('contend')

@livewire('cliente.gestion-clientes')

@endsection
