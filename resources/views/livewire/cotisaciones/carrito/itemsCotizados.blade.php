<div>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @include('livewire/cotisaciones/carrito/itemsCotizadosStock')
    @include('livewire/cotisaciones/carrito/itemsCotizadosProvedor')

</div>
