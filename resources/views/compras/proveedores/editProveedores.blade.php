@extends('layouts.app')
@section('title', 'Editar Proveedor')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeProveedores', 'active')
@section('activeFondoPermanenteProveedores', 'background-permanent')
@section('contend')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
crossorigin=""/>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
crossorigin=""></script>

<style>
    .file-upload {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #007bff;
        border-radius: 5px;
        background-color: #f9f9f9;
        padding: 20px;
        cursor: pointer;
    }

    .file-upload .file-upload-icon {
        font-size: 48px;
        color: #007bff;
    }

    .file-upload .file-upload-text {
        margin-left: 15px;
        font-size: 16px;
        color: #007bff;
    }

    .file-upload input[type="file"] {
        display: none;
    }

    .btn-round {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        padding: 0;
        font-size: 24px;
        text-align: center;
        line-height: 36px;
    }
</style>
<style>
    #map {
        height: 180px;
    }
</style>
@livewire('proveedor.edit-proveedor', ['idproveedor' => $idproveedor])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection