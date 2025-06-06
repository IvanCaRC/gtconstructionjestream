<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <style>
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
            cliente margin-left: 1rem;
        }
    </style>
    <br>
    <div class="ml-3">
        <div class="row align-items-center">
            <button type="button" class="btn-icon" wire:click="regresarGestionClientes">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h2 class="ml-3">Ordenes de compra </h2>
        </div>
    </div>

    @include('livewire.cotisaciones.ordene-compra.vistaEspecificaOrdenCompra.cardProyecto')
<br>
    @include('livewire.cotisaciones.ordene-compra.vistaEspecificaOrdenCompra.cardListaOrdenesCompra')



</div>
