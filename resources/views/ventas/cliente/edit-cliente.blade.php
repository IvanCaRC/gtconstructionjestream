@extends('layouts.app')
@section('title', 'edit de Clientes')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')

@section('activeGestionClientes', 'active')
@section('activeFondoPermanenteGestionCLientes', 'background-permanent')
@section('contend')



<div class="container-fluid mb-6 px-4 sm:px-6 lg:px-8 py-6">


    <div class="card">
        <div class="card-header">
            <h2>Formulario de recepcion de llamada</h2>
        </div>
        <div class="card-body">
            <h3>Datos del cliente</h3>
            @livewire('cliente.edit-cliente',['idcliente' => $idcliente])
        </div>
    </div>
</div>
@endsection