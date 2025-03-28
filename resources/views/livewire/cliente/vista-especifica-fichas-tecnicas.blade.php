<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            <button class="btn btn-light border-0 shadow-sm " style="width: 50px; height: 50px;"
                wire:click="verLista({{ $idLista }})">
                <i class="fas fa-shopping-cart text-primary" style="font-size: 24px;"></i>
            </button>
        </div>
    </div>

    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-4">
        <div>
            <div>
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <button type="button" class="btn-icon" onclick="cancelar()">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h2 class="ml-3">Detalle Item</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container">

                            <div class="row">
                                <!-- Área de carga de la imagen -->

                                <div class="col-md-4">
                                    <div class="form-group">

                                        <div class="form-group text-center mt-3">
                                            @if ($imagenesCargadas == null || count($imagenesCargadas) == 0)
                                                <div class="imagen-predeterminada">
                                                    <span class="file-upload-icon">&#128247;</span>
                                                    <span class="file-upload-text">Sin imagenes, sube tu editando el
                                                        item</span>
                                                </div>
                                            @else
                                                <div class="galeria">
                                                    <div class="imagen-grande-container">
                                                        <img id="imagenGrande"
                                                            src="{{ asset('storage/' . $imagenesCargadas[0]) }}"
                                                            alt="Imagen Principal" class="imagen-grande">
                                                    </div>
                                                </div>
                                                {{-- Miniaturas --}}
                                                <div class="miniaturas-container mt-3">
                                                    @foreach ($imagenesCargadas as $index => $imaCarg)
                                                        <div class="miniatura">
                                                            <img src="{{ asset('storage/' . $imaCarg) }}"
                                                                alt="Miniatura" class="imagen-miniatura"
                                                                onclick="cambiarImagenPorIndice({{ $index }})">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <br>


                                            @if (in_array($itemEspecifico->id, $itemsEnLista))
                                                <button class="btn btn-warning btn-custom"
                                                    wire:click="verLista({{ $idLista }})"
                                                    title="Este item ya está en tu lista">
                                                    <i class="fas fa-shopping-cart"></i> Ver en carrito
                                                </button>
                                            @else
                                                <div class="d-flex justify-content-center align-items-center mt-2">
                                                    <!-- Botón de menos -->
                                                    <button class="btn btn-danger btn-sm me-2"
                                                        wire:click="decrementarCantidad">-</button>

                                                    <!-- Input de cantidad -->
                                                    <input type="number" min="1"
                                                        class="form-control text-center" style="width: 60px;"
                                                        wire:model="cantidad">

                                                    <!-- Botón de más -->
                                                    <button class="btn btn-success btn-sm ms-2"
                                                        wire:click="incrementarCantidad">+</button>
                                                </div>
                                                <br>
                                                <div>
                                                    <button class="btn btn-success btn-custom"
                                                        wire:click="agregarItemLista({{ $itemEspecifico->id }})"
                                                        title="Agrega este item a tu lista">
                                                        <i class="fas fa-shopping-cart"></i> Añadir a la lista
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-1">
                                </div>
                                <div class="col-md-7">

                                    <div class="form-group">
                                        <h1 for="">{{ $item->nombre }}</h1>
                                        @if ($itemEspecifico->estado)
                                            <span class="badge badge-success">Actualizado</span>
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
                                            <label for="moc" class="mr-2">MOC (Mínimo de venta a cliente)</label>

                                            <br>
                                            <label for="">{{ $itemEspecifico->moc }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2 mb-3">
                                                <label for="cantidad_piezas_mayoreo" class="mr-2">Cant. Piezas
                                                    Mayoreo</label>
                                                <label
                                                    for="">{{ $itemEspecifico->cantidad_piezas_mayoreo }}</label>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="precio_venta_mayorista" class="mr-2"><br>Precio
                                                    Mayorista</label>
                                                <label
                                                    for="">{{ $itemEspecifico->precio_venta_mayorista }}</label>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="precio_venta_minorista" class="mr-2"><br>Precio
                                                    Minorista</label>
                                                <label
                                                    for="">{{ $itemEspecifico->precio_venta_minorista }}</label>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2 mb-3">

                            </div>
                            <div class="col-md-2 mb-3">
                                <div id="pdf-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pdfUrl = "{{ asset('storage/' . $itemEspecifico->ficha_tecnica_pdf) }}";

            pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
                let container = document.getElementById("pdf-container");
                container.innerHTML = ""; // Limpiar el contenedor

                for (let i = 1; i <= pdf.numPages; i++) {
                    pdf.getPage(i).then(page => {
                        let canvas = document.createElement("canvas");
                        let context = canvas.getContext("2d");
                        container.appendChild(canvas);

                        let viewport = page.getViewport({
                            scale: 1.5
                        });
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;

                        let renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                }
            });
        });
    </script>
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
            window.location.href = "{{ route('compras.items.viewItems') }}";
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
</div>
