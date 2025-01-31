@extends('layouts.app')
@section('title', 'Editar Proveedor')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeProveedores', 'active')
@section('activeFondoPermanenteProveedores', 'background-permanent')
@section('contend')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/proveedor.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
            <h1>Crear Nuevo Proveedor</h1>

            <div class="card">
                <div class="card-body">
                    
                    <form id="proveedor-form" action="{{ route('compras.proveedores.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="direcciones-input" name="direcciones">
                        <input type="hidden" id="proveedor-id-input" name="proveedor_id" value="">

                        @livewire('proveedor.edit-proveedor', ['idproveedor' => $idproveedor])
                        <div class="form-group">
                            <label>Direcciones</label>
                            <div class="input-group mb-2" id="address-list">
                                <!-- Aquí se mostrarán las direcciones guardadas -->
                                <p>No hay direcciones guardadas.</p>
                            </div>
                        </div>@include('compras.proveedores.form')
                        
                        
                    
                        <button  onclick="confirmUpdate()" class="btn btn-primary mt-3">Actualizar proveedor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
    <script src="{{ asset('js/proveedor.js') }}"></script>
    <script>
        let savedAddresses = @json($direcciones); // Cargar las direcciones desde Laravel
    </script>
    

@endsection