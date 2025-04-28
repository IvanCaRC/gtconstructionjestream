<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <div class="">

        <div class="row">
            <!-- Área de carga de la imagen -->

            <div class="col-md-2">


                <div class="form-group"
                    style="display: flex; flex-direction: column; align-items: center; justify-content: center;">

                    <div class="form-group text-center mt-3">
                        @if ($imagenesCargadas == null || count($imagenesCargadas) == 0)
                            <div class="imagen-predeterminada" style="width: 250px; height: 250px; object-fit: cover;">
                                <span class="file-upload-icon">&#128247;</span>
                                <span class="file-upload-text">Sin imagenes, sube tu editando el
                                    item</span>
                            </div>
                        @else
                            <div class="galeria">
                                <div class="imagen-grande-container">
                                    <img id="imagenGrande" src="{{ asset('storage/' . $imagenesCargadas[0]) }}"
                                        alt="Imagen Principal" style="width: 250px; height: 250px; object-fit: cover;">
                                </div>


                            </div>
                            {{-- Miniaturas --}}
                            <div class="miniaturas-container mt-3">
                                @foreach ($imagenesCargadas as $index => $imaCarg)
                                    <div class="miniatura">
                                        <img src="{{ asset('storage/' . $imaCarg) }}" alt="Miniatura"
                                            class="imagen-miniatura"
                                            onclick="cambiarImagenPorIndice({{ $index }})">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
            <script>
                let imagenes = @json($imagenesCargadas); // Array de imágenes desde Blade
                let indiceActual = 0;

                function actualizarImagen() {
                    document.getElementById("imagenGrande").src = `/storage/${imagenes[indiceActual]}`;
                }


                function cambiarImagenPorIndice(index) {
                    indiceActual = index;
                    actualizarImagen();
                }
            </script>

            <style>
                .imagen-grande {
                    width: 400px;
                    /* O el tamaño que necesites */
                    height: 400px;
                    /* O el tamaño que necesites */
                    object-fit: cover;
                    /* Ajusta la imagen sin deformarla */
                }


                .miniaturas-container {
                    display: flex;
                    justify-content: center;
                    gap: 10px;
                    flex-wrap: wrap;
                }

                .miniatura {
                    width: 70px;
                    height: 70px;
                    overflow: hidden;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: transform 0.3s ease;
                }

                .miniatura:hover {
                    transform: scale(1.1);
                }

                .imagen-miniatura {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .imagen-grande-container {
                    position: relative;
                    width: 100%;
                    max-width: 500px;
                    margin: auto;
                }

                .badge.bg-success,
                .badge.bg-danger {
                    color: white !important;
                }
            </style>

            <div class="col-md-4">

                <div class="form-group">
                    <h1 for="">{{ $item->nombre }}</h1>
                    @if ($itemEspecifico->estado)
                        <e class="badge badge-success">Actualizado</span>
                    @else
                        <span class="badge badge-danger">Desactualizado</span>
                        <p class="card-text text-muted">
                            Última actualización:
                            {{ $itemEspecifico->item->updated_at->format('d/m/Y') }}
                        </p>
                    @endif
                </div>

                <div class="form-group">
                    <h3 for="">Descripción</h3>
                    <label for="">{{ $item->descripcion }} </label>
                </div>

                <div class="form-group">
                    <h3 for="">Marca</h3>
                    <label for="">{{ $itemEspecifico->marca }} </label>
                </div>
                <div class="form-group">
                    <style>
                        .enunciado-label {
                            color: #333;
                            /* Color más oscuro */
                            font-size: 1.25rem;
                            /* Tamaño de letra más grande */
                            font-weight: bold;
                            /* Texto en negritas */
                            margin-right: 5px;
                            /* Espacio entre labels */
                        }

                        .input-group {
                            display: flex;
                            align-items: center;
                            /* Alinea verticalmente los labels */
                        }
                    </style>
                    @if ($especificaciones)
                        @foreach ($especificaciones as $index => $especificacion)
                            <div class="input-group mb-2">
                                <label class="enunciado-label">
                                    {{ $especificacion['enunciado'] }}:
                                </label>
                                <label>
                                    {{ $especificacion['concepto'] }}
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Familias</label>
                        <div class="input-group mb-2">
                            @if (count($familiasSeleccionadas) > 0)
                                @foreach ($familiasSeleccionadas as $index => $familia)
                                    <div class="w-100 d-flex align-items-center mb-2">
                                        <div class="flex-grow-1">
                                            {{ $familia['nombre'] }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="no-familias-seleccionadas w-100">
                                    No hay familias seleccionadas
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h4 for="unidad">Unidad</h4>
                        <label>{{ $itemEspecifico->unidad }}</label>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="stock" class="mr-2">Stock Actutal del Producto</label>
                        <br>
                        <label for="">{{ $itemEspecifico->stock }}</label>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="moc" class="mr-2">MOC (Mínimo de venta a cliente)</label>

                        <br>
                        <label for="">{{ $itemEspecifico->moc }}</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="cantidad_piezas_mayoreo" class="mr-2">Cant. Piezas
                                Mayoreo</label>
                            <label for="">{{ $itemEspecifico->cantidad_piezas_mayoreo }}</label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="porcentaje_venta_mayorista" class="mr-2">% Venta
                                Mayorista</label>
                            <label for="">{{ $itemEspecifico->porcentaje_venta_mayorista }}</label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="porcentaje_venta_minorista" class="mr-2">% Venta
                                Minorista</label>
                            <label for="">{{ $itemEspecifico->porcentaje_venta_minorista }}</label>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-md-6">
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($listaUsuarioActiva == null)
                    <p>No hay una cotisacion activa, seleciona una para poder cotizar</p>
                @else
                    @include('livewire.items-cotizar.elccion-provedores')
                @endif

            </div>
        </div>

    </div>

    <script>
        function confirmDeletion(itemEspecificoId, itemEspecificoNombre) {
            Swal.fire({
                title: `¿Estás seguro de que deseas eliminar  ${itemEspecificoNombre}?`,
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('eliminar', itemEspecificoId);
                    Swal.fire({
                        title: 'ELiminado',
                        text: 'el item fue eliminado correctamenteha sido creada exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                    }).then((result) => {
                        // Redirigir al hacer clic en el botón "OK"
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('compras.items.viewItems') }}";
                        }
                    });
                }
            })
        }
    </script>
    <script>
        function cancelar() {
            // Llamar al método update2 de Livewire
            window.location.href = "{{ route('compras.catalogoCotisacion.catalogoItem') }}";
        }
    </script>
    <script>
        let imagenes = @json($imagenesCargadas); // Array de imágenes desde Blade
        let indiceActual = 0;

        function actualizarImagen() {
            document.getElementById("imagenGrande").src = `/storage/${imagenes[indiceActual]}`;
        }


        function cambiarImagenPorIndice(index) {
            indiceActual = index;
            actualizarImagen();
        }
    </script>

    <script>
        function validatePhoneInput(element) {
            // Permitir solo números, espacios y el signo de +
            element.value = element.value.replace(/[^0-9\s+]/g, '');

            // Limitar la longitud a 16 caracteres
            if (element.value.length > 7) {
                element.value = element.value.substring(0, 7);
            }
        }
    </script>
</div>
