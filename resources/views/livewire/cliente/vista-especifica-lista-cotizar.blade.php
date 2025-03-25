<div>
    <div class="row bg-white py-4  shadow">

        @if ($listadeUsuarioActiva == null)
            <div class="col-md-9">
                <h4 class="px-3">
                    No hay una lista activa. Activa o crea una para realizar la lista.
                </h4>
            </div>
        @else
            <div class="col-md-9">
                <h4 class="px-3">
                    Lista activa de cliente "<span class="fw-bold text-primary ">{{ $nombreCliente }}</span>",
                    del proyecto "<span class="fw-bold text-primary ">{{ $nombreProyecto }}</span>",
                    y lista "<span class="fw-bold text-primary ">{{ $listadeUsuarioActiva }}</span>".
                </h4>
            </div>
        @endif
        <div class="col-md-1">
            <a href="#" class=" text-danger d-block">Cancelar</a>
        </div>
        <div class="col-md-1">
            <a href="#" class="d-block">Desactivar</a>
        </div>
        <div class="col-md-1">
            <button class="btn btn-light border-0 shadow-sm " style="width: 50px; height: 50px;"  wire:click="verLista({{$idLista}})">
                <i class="fas fa-shopping-cart text-primary" style="font-size: 24px;"></i>
            </button>
        </div>
    </div>


    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="flex h-screen gap-4 p-4">
            <!-- Primera sección (85%) -->
            <div class="flex-1 bg-white p-4 rounded-lg border border-black" style="flex: 0 0 80%;">
                <div class="card-body">



                </div>
            </div>
            <!-- Segunda sección (15%) -->
            <div class="bg-white rounded-lg border border-black p-4" style="flex: 0 0 20%;">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>
