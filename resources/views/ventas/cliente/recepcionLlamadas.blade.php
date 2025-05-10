@extends('layouts.app')
@section('title', 'Recepcion de llamada')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')

@section('activeRecepcion', 'active')
@section('activeFondoPermanenteRecepcion', 'background-permanent')
@section('contend')
    <link rel="stylesheet" href="{{ asset('css/proveedor.css') }}">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">


        <div class="card">
            <div class="card-header">
                <h2>Formulario de recepcion de llamada</h2>
            </div>
            <div class="card-body">
                {{-- <h3>Datos del cliente</h3> --}}
                <form id="proveedor-formee" action="{{ route('ventas.clientes.recepcionLlamadas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="direcciones-input" name="direcciones">
                    <input type="hidden" id="cliente-id-input" name="cliente_id" value="">
                    @livewire('cliente.recepcion-llamada')
                    <div class="form-group">
                        <label>Direcciones</label>
                        <div class="input-group mb-2" id="address-list">
                            <!-- Aquí se mostrarán las direcciones guardadas -->
                            <p>No hay direcciones guardadas.</p>
                        </div>
                    </div>@include('compras.proveedores.form')
                    <button class="btn btn-secondary mt-3" onclick="cancelar()">Cancelar</button>
                    <button type="submit" onclick="confirmSave()" class="btn btn-primary mt-3">Registrar cliente</button>
                </form>
            </div>
        </div>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script src="{{ asset('js/proveedor.js') }}"></script>

        <script>
            function validateCoordinateKey(event) {
                const allowedKeys = ["Backspace", "Tab", "Delete", "ArrowLeft", "ArrowRight", "Home", "End"];
                const char = event.key;

                // Permitir teclas de control (borrar, tab, flechas, etc.)
                if (allowedKeys.includes(char)) {
                    return true;
                }

                // Permitir solo números, punto, + y -
                if (!/^[0-9.+-]$/.test(char)) {
                    event.preventDefault();
                    return false;
                }

                return true;
            }

            function validateCoordinateValue(input) {
                // Eliminar cualquier carácter no permitido después de ingresarlo
                input.value = input.value.replace(/[^0-9.+-]/g, '');
                if (input.value.length > 13) {
                    input.value = input.value.substring(0, 17);
                }
            }
        </script>
    @endsection
