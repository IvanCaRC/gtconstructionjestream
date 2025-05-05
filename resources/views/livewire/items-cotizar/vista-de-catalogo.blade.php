<div>
    <div class="row bg-white py-4  shadow">

        @if ($listadeUsuarioActiva == null)
            <div class="col-md-12">
                <div class="text-center p-2 border rounded bg-light shadow-sm">
                    <h5 class="text-danger font-weight-bold">⚠ No tienes ninguna cotización activa</h5>
                    <p class="text-muted small">Activa una lista para poder realizar la cotización.</p>
                    <a href="{{ route('compras.cotisaciones.verMisCotisaciones') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="fas fa-list-ul"></i> Seleccionar una lista
                    </a>
                </div>
            </div>
        @else
            <div class="col-md-8">
                <h4 class="px-3 text-success font-weight-bold">
                    ✅ Cotización activa:
                    <span class="text-primary">"{{ $listadeUsuarioActiva }}"</span>
                </h4>
            </div>
            <div class="col-md-1">
                <button class="btn btn-outline-primary btn-sm font-weight-bold px-3 py-2 shadow"
                    onclick="accionCotisacion()">
                    <i class="fas fa-paper-plane"></i> Enviar cotización
                </button>
            </div>
            <div class="col-md-1">
                <button class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-2 shadow"
                    wire:click="desactivarLista({{ $idCotizaciones }})">
                    <i class="fas fa-ban"></i> Desactivar
                </button>
            </div>
            <div class="col-md-1">
                <button class="btn btn-light border-0 shadow-sm " style="width: 50px; height: 50px;"
                    wire:click="verLista({{ $idCotizaciones }})">
                    <i class="fas fa-shopping-cart text-primary" style="font-size: 24px;"></i>
                </button>
            </div>
        @endif


    </div>
    <script>
        function accionCotisacion() {
            Swal.fire({
                title: "<strong>¿Confirmas el envío al departamento de compras?</strong>",
                html: "<p class='text-muted'>Esta acción no se puede deshacer.</p>",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "<i class='fas fa-paper-plane'></i> Enviar cotización"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Procesando...",
                        text: "Por favor, espera mientras enviamos tu cotización.",
                        icon: "info",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
    
                    @this.call('enviar')
                        .then(response => {
                            Swal.fire({
                                title: "¡Cotización enviada!",
                                text: "La cotización ha sido enviada con éxito.",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                window.location.href = 
                                    "{{ route('compras.cotisaciones.verMisCotisaciones') }}";
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                title: "Error al enviar",
                                text: "Hubo un problema. Inténtalo de nuevo.",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        });
                }
            });
        }
    </script>
</div>
