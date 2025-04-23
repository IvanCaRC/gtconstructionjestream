<div>
    <div class="row bg-white py-4  shadow">

        @if ($listadeUsuarioActiva == null)
            <div class="col-md-12">
                <h4 class="px-5">
                    No hay una cotisacion activa. Activa una para poder realizar la cotisacion
                </h4>
            </div>
        @else
            <div class="col-md-10">
                <h4 class="px-3">

                    Cotizacion "<span class="fw-bold text-primary ">{{ $listadeUsuarioActiva }}</span>" activa.
                </h4>
            </div>
            <div class="col-md-1">
                <a href="#" wire:click="desactivarLista({{ $idCotizaciones }})" class="d-block">Desactivar</a>
            </div>
            <div class="col-md-1">
                <button class="btn btn-light border-0 shadow-sm " style="width: 50px; height: 50px;"
                    wire:click="verLista({{ $idCotizaciones }})">
                    <i class="fas fa-shopping-cart text-primary" style="font-size: 24px;"></i>
                </button>
            </div>
        @endif


    </div>
</div>
