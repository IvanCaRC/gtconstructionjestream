@extends('layouts.app')
<!-- Incluir Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha384-hs2+e1ecAhXZrp0TBECMsTh0FeP0K2j3/Zni+1iZQODhFboc2vm4IpYgrbs4zXrC" crossorigin=""/>

<!-- Incluir Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha384-M0jX1lz+V5Ftchz4HJaP3vTTv1od8Hcewgsq/oa9OYypsA8SjlAZ5d+8xvQZZlAA" crossorigin=""></script>

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
@section('title', 'Crear proveedor')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeProveedores', 'active')
@section('activeFondoPermanenteProveedores', 'background-permanent')
@section('contend')
@livewire('proveedor.create-proveedor')
@endsection