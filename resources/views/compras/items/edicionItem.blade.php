@extends('layouts.app')
@section('title', 'Edicion Item')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeMateriales', 'active')
@section('activeFondoPermanenteMateriales', 'background-permanent')
@section('contend')
    <style>
        .form-group .row .col-md-2 {
            margin-bottom: 1rem;
        }

        .form-group .mr-2 {
            margin-right: 10px;
        }

        .form-control-static {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 8px 12px;
            background-color: #e9ecef;
        }

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

        .file-upload3 {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #007bff;
            border-radius: 5px;
            background-color: #f9f9f9;
            padding: 20px;
            cursor: pointer;
        }

        .file-upload3 .file-upload-icon 3 {
            font-size: 48px;
            color: #007bff;
        }

        .file-upload3 .file-upload-text3 {
            margin-left: 15px;
            font-size: 16px;
            color: #007bff;
        }

        .file-upload3 input[type="file"] {
            display: none;
        }

        .btn-round3 {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            padding: 0;
            font-size: 24px;
            text-align: center;
            line-height: 36px;
        }

        .imagen-predeterminada {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #007bff;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: 350px;
            height: 350px;
            margin: 0 auto;
            flex-direction: column;
            pointer-events: none;
            /* Evita que parezca interactivo */
        }

        .imagen-predeterminada .file-upload-icon {
            font-size: 48px;
            color: #007bff;
        }

        .imagen-predeterminada .file-upload-text {
            font-size: 16px;
            color: #007bff;
            margin-top: 10px;
        }

        .imagen-cuadrada {
            width: 350px;
            height: 350px;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }

        .file-input {
            display: none;
        }
    </style> 
    @livewire('item.edit-item', ['idItem' => $idItem])
@endsection
