<div>
    
    @if (in_array($itemEspecifico->id, $itemsEnLista) || in_array($itemEspecifico->id, $itemsEnListaProveedores))
        <h4>El item ya se encuentra en la cotisacion</h4>
        <button class="btn btn-warning btn-custom" title="Este item ya está en tu lista" >
            <i class="fas fa-shopping-cart"></i> Cambiar modalidad
        </button>
        <button class="btn btn-primary btn-custom" title="Este item ya está en tu lista" wire:click="verLista({{ $idCotizaciones }})">
            <i class="fas fa-shopping-cart"></i> Ver en lista
        </button>
    @else
        <div class="form-group">

            @if ($tipoCotizacion == null)
                <h4>
                    Seleciona el tipo de cotisacion que realizaras
                </h4>
                <button href="#" wire:click="cambiarStock()" class="btn btn-primary mt-3">Cotisacion de
                    stock</button>
                <button href="#" wire:click="cambiarProveedor()" class="btn btn-primary mt-3">Cotizacion de
                    proveedor</button>
                <br>
            @else
                @if ($tipoCotizacion == 1)
                    <h4>
                        Cambiar tipo de cotizacion
                    </h4>
                    <button href="#" wire:click="cambiarStock()" class="btn btn-primary mt-3">Cotisacion de
                        stock</button>
                @endif
                @if ($tipoCotizacion == 2)
                    <h4>
                        Cambiar tipo de cotizacion
                    </h4>
                    <button href="#" wire:click="cambiarProveedor()" class="btn btn-primary mt-3">Cotizacion de
                        proveedor</button>
                @endif
            @endif
            @if ($tipoCotizacion == 1)
                @include('livewire.items-cotizar.tipoCotizacion1')
            @endif
            @if ($tipoCotizacion == 2)
                @include('livewire.items-cotizar.tipoCotizacion2')
            @endif
        </div>

    @endif
</div>
