<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3">
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        Resumen
                    </div>
                    <div class="col-md-6">
                        <button>Crear cotizacion</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Items solicitados</h3>
                        <div>
                            @include('livewire.cotisaciones.carrito.itemsSolicitados')
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Items cotizados</h3>
                        <div>
                            @include('livewire.cotisaciones.carrito.itemsCotizados')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
