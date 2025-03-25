@extends('layouts.app')
@section('title', 'Item Especifico')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')
@section('activeFichasTecnicas', 'active')
@section('activeFondoPermanenteFichasTecnicas', 'background-permanent')
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

        .btn-icon {
            display: flex;
            align-items: center;
            background-color: transparent;
            color: #6c757d;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .btn-icon:hover {
            color: #5a6268;
        }

        .btn-icon i {
            margin-right: 5px;
        }

        .row.align-items-center {
            display: flex;
            align-items: center;
        }

        .ml-3 {
            margin-left: 1rem;
        }

        .imagen-grande {
            width: 400px;
            /* O el tamaño que necesites */
            height: 400px;
            /* O el tamaño que necesites */
            object-fit: cover;
            /* Ajusta la imagen sin deformarla */
        }


        .miniaturas-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .miniatura {
            width: 70px;
            height: 70px;
            overflow: hidden;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .miniatura:hover {
            transform: scale(1.1);
        }

        .imagen-miniatura {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .imagen-grande-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            margin: auto;
        }

        .badge.bg-success,
        .badge.bg-danger {
            color: white !important;
        }
    </style>
    @livewire('cliente.vista-especifica-fichas-tecnicas', ['idItem' => $idItem])
@endsection
