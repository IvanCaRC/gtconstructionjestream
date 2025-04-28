<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <h3>Resumen</h3>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-primary btn-sm" onclick="confirmSave()">
                            Terminar cotisacion
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Lista a cotizar</h5>
                        <div>
                            <label for="">Cantidad de items de la lista: {{ $cantidadDeItemsLista }}</label>
                        </div>
                        <div>
                            <label for="">Cantidad de items de la lista cotisados:
                                {{ $cantidadItemsActivos }}</label>
                        </div>
                        <div>
                            <label for="">Cantidad de items faltantes por cotisar:
                                {{ $cantidadItemsInactivos }}</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5>Cotizacion</h5>
                        <div>
                            <label for="">Items de cotisacion usando el Stock:
                                {{ $cantidadDeItemsCotizacionStock }}</label>
                        </div>
                        <div>
                            <label for="">Items de cotisacion usando el Proveedor:
                                {{ $cantidadDeItemsCotizacionProveedor }}</label>
                        </div>
                        <div>
                            <label for="">Cantidad de actuales en la cotizacion:
                                {{ $cantidadDeItemsCotizacion }}</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5>Precios</h5>
                        <div>
                            <label for="">Precios totales Stock: {{ number_format($precioStock, 2) }}</label>
                        </div>
                        <div>
                            <label for="">Precios totales Proveedor:
                                {{ number_format($precioProveedor, 2) }}</label>
                        </div>
                        <div>
                            <label for="">Precio total:
                                {{ number_format($precioProveedor + $precioStock, 2) }}</label>
                        </div>
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
    <script>
        function confirmSave() {
            // Llamar al método save de Livewire
            @this.call('enviarLista').then(response => {
                if (response) {
                    // Mostrar la alerta después de la creación si todo es correcto
                    Swal.fire({
                        title: "Custom width, padding, color, background.",
                        width: 600,
                        padding: "3em",
                        color: "#716add",
                        background: "#fff url(/images/trees.png)",
                        backdrop: `
    rgba(0,0,123,0.4)
    url("https://sweetalert2.github.io/images/nyan-cat.gif")
    left top
    no-repeat
  `
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al crear el item.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>
</div>
