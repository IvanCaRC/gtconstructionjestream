@extends('layouts.app')
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