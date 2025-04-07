@extends('layouts.app')
@section('title', 'Editar cliente')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')

@section('activeGestionClientes', 'active')
@section('activeFondoPermanenteGestionCLientes', 'background-permanent')
@section('contend')



<div class="container-fluid mb-6 px-4 sm:px-6 lg:px-8 py-6">


    <div class="card">
        <div class="card-header">
            <h2>Edicion de cliente</h2>
        </div>
        <div class="card-body">
            <h3>Datos del cliente</h3>
            @livewire('cliente.edit-cliente',['idcliente' => $idcliente])
            <div class="form-group">
                <label>Direcciones</label>
                <div class="input-group mb-2" id="address-list">
                    <!-- Aquí se mostrarán las direcciones guardadas -->
                    <p>No hay direcciones guardadas.</p>
                </div>
            </div>@include('compras.proveedores.form')
            <button class="btn btn-secondary mt-3" onclick="cancelar()">Cancelar</button>
            <button type="submit" onclick="confirmUpdate()" class="btn btn-primary mt-3">Actualizar cliente</button>
        </div>
    </div>
</div>
@endsection