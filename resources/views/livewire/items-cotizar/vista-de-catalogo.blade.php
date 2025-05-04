<div>
    <div class="row bg-white py-4  shadow">

        @if ($listadeUsuarioActiva == null)
            <div class="col-md-12">
                <h4 class="px-5">
                    No hay una cotizacion activa. Activa una para poder realizar la cotisacion
                </h4>
            </div>
        @else
            <div class="col-md-8">
                <h4 class="px-3">
                    Cotizacion "<span class="fw-bold text-primary ">{{ $listadeUsuarioActiva }}</span>" activa.
                </h4>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm" onclick="accionCotisacion()">
                    Enviar cotizacion
                </button>
            </div>
            <div class="col-md-1">
                <button class="btn btn-outline-danger btn-sm" wire:click="desactivarLista({{ $idCotizaciones }})">
                    Desactivar
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
            // Llamar al método enviar de Livewire
            @this.call('enviar').then(response => {
                if (response) {
                    // Mostrar la alerta después de la actualización si todo es correcto
                    Swal.fire({
                        title: "¿Estás seguro que deseas enviar la cotización al cliente?",
                        text: "Esta acción no la puedes deshacer.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, Enviar!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "¡Enviada!",
                                text: "La cotización ha sido enviada.",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                // Redirigir después de la confirmación
                                window.location.href = "{{ route('compras.cotisaciones.verMisCotisaciones') }}";
                            });
                        }
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo enviar la cotización.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>
    
</div>
