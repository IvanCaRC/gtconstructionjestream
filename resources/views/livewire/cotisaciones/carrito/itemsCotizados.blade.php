<div>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (count($itemsCotisacionProveedor) > 0 || count($itemCotisacionStock) > 0)
    @else
        <label for="">Sin items en la cotisacion</label>
    @endif
    @include('livewire/cotisaciones/carrito/itemsCotizadosStock')
    @include('livewire/cotisaciones/carrito/itemsCotizadosProvedor')

</div>
