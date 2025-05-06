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
            margin-left: 1rem;
        }
    </style>
    <div class="ml-3">
        <div class="row align-items-center">
            <button type="button" class="btn-icon" wire:click="regresarGestionClientes"public
                <i class="fas fa-arrow-left"></i>
            </button>
            <h2 class="ml-3">Detalles de cliente</h2>
        </div>
    </div>
    @include('livewire.cliente.card-cliente')
    <div>
        <br>
    </div>
    @include('livewire.cliente.vistaDeproyecto.card-vista-proyecto-esp')
    <br>
    @include('livewire.cliente.vistaDeproyecto.card-vista-tabla-lista')
    <br>
    @include('livewire.cliente.modal-actualizacion-proyecto')
</div>
