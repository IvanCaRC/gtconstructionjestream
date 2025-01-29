

@extends('layouts.app')

@section('title', 'Crear proveedor')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeProveedores', 'active')
@section('activeFondoPermanenteProveedores', 'background-permanent')
@section('contend')
    <link rel="stylesheet" href="{{ asset('css/proveedor.css') }}">

    <div class="container-fluid">
        <h1>Crear Nuevo Proveedor</h1>

        <div class="card">
            <div class="card-body">
                @include('compras.proveedores.form')

            </div>
        </div>
    </div>

    @include('compras.proveedores.modales') {{-- Separar modales en un archivo parcial --}}

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/proveedor.js') }}"></script>

    {{-- @livewire('proveedor.create-proveedor') --}}
@endsection
