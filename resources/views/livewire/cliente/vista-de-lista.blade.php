<div>
    <div class="row bg-white py-4  shadow">

        @if ($idLista == null)
            <div class="col-md-12">
                <h4 class="px-5">
                    No hay una lista activa. Activa o crea una para realizar la lista.
                </h4>
            </div>
        @else
            @if ($nombreCliente == 'Sin cliente')
                <div class="col-md-8">
                    <h4 class="px-5">
                        La lista que se encuentra actualmente activa no cuenta con un cliente y proyecto asignado.
                    </h4>
                </div>
                <button class="btn btn-outline-success shadow-sm fw-bold"
                    onclick="window.location.href='{{ route('ventas.clientes.gestionClientes') }}'">
                    <i class="fas fa-list"></i> Seleccionar lista
                </button>
                <div class="col-md-1">
                    <a href="#" wire:click="desactivarLista({{ $idLista }})"
                        class="btn btn-outline-danger shadow-sm fw-bold text-center">
                        <i class="fas fa-times"></i> Desactivar
                    </a>
                </div>
                <div class="col-md-1">
                    <button
                        class="btn btn-light border-0 shadow-sm rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 50px; height: 50px;" wire:click="verLista({{ $idLista }})">
                        <i class="fas fa-shopping-cart text-primary" style="font-size: 24px;"></i>
                    </button>
                </div>
            @else
                <div class="col-md-8 mx-3"> <!-- Reducimos el ancho y agregamos margen lateral -->
                    <div class="card shadow-sm border-0 bg-light p-2">
                        <h5 class="text-dark fw-bold">
                            <i class="fas fa-user-circle text-primary"></i> Cliente:
                            <span class="fw-bold text-primary">{{ $nombreCliente }}</span>
                        </h5>
                        <h5 class="text-dark fw-bold">
                            <i class="fas fa-folder-open text-success"></i> Proyecto:
                            <span class="fw-bold text-success">{{ $nombreProyecto }}</span>
                        </h5>
                        <h5 class="text-dark fw-bold">
                            <i class="fas fa-list text-warning"></i> Lista activa:
                            <span class="fw-bold text-warning">{{ $listadeUsuarioActiva }}</span>
                        </h5>
                    </div>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('ventas.fichasTecnicas.fichasTecnicas') }}"
                        class="btn btn-outline-primary shadow-sm fw-bold text-center" style="height: 60px;">
                        <i class="fas fa-book-open"></i> Ir al catálogo
                    </a>
                </div>
                <div class="col-md-1"> <a href="#" wire:click="desactivarLista({{ $idLista }})"
                        class="btn btn-outline-danger shadow-sm fw-bold text-center"> <i class="fas fa-times"></i>
                        Desactivar </a> </div>
                <div class="col-md-1">
                    <button class="btn btn-light border-0 shadow-sm " style="width: 50px; height: 50px;"
                        wire:click="verLista({{ $idLista }})">
                        <i class="fas fa-shopping-cart text-primary" style="font-size: 24px;"></i>
                    </button>
                </div>
            @endif


        @endif


    </div>
</div>
