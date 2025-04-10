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

    
    @livewire('cliente.vista-de-lista')

    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <button type="button" class="btn-icon" onclick="cancelar()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h2 class="ml-3">Detalle Item</h2>
                </div>
            </div>
            <div class="card-body">
                @livewire('cliente.vista-especifica-fichas-tecnicas', ['idItem' => $itemEspecifico->id])
                <div class="row">
                    <div class="col-md-2 mb-3">
            
                    </div>
                    <div class="col-md-2 mb-3">
                        <div id="pdf-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pdfUrl = "{{ asset('storage/' . $itemEspecifico->ficha_tecnica_pdf) }}";

            pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
                let container = document.getElementById("pdf-container");
                container.innerHTML = ""; // Limpiar el contenedor

                for (let i = 1; i <= pdf.numPages; i++) {
                    pdf.getPage(i).then(page => {
                        let canvas = document.createElement("canvas");
                        let context = canvas.getContext("2d");
                        container.appendChild(canvas);

                        let viewport = page.getViewport({
                            scale: 1.5
                        });
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;

                        let renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                }
            });
        });
    </script>
@endsection
