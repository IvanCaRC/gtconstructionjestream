<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <div>
        <div>
            <div>
                <div class="card">
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
                                <div class="col-md-1">
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <h1 for="">{{ $item->nombre }}</h1>
                                    </div>
                                    <div class="form-group">
                                        <h3 for="">Descripcion</h3>
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
                                    <div class="form-group">
                                        @if (count($ProvedoresAsignados) > 1)
                                            <label> Provedores</label>
                                        @endif
                                        @if (count($ProvedoresAsignados) > 0 && count($ProvedoresAsignados) < 2)
                                            <label> proveedor</label>
                                        @endif
                                        <div class="input-group mb-2">
                                            @if (count($ProvedoresAsignados) > 0)
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Nombre</th>
                                                            <th>Tiempo Mínimo de Entrega</th>
                                                            <th>Tiempo Máximo de Entrega</th>
                                                            <th>Precio de Compra</th>
                                                            <th>Unidad</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($ProvedoresAsignados as $index => $conexion)
                                                            @php
                                                                $conexionObjeto = (object) $conexion;
                                                            @endphp
                                                            <tr>
                                                                <style>
                                                                    .checkbox-btn {
                                                                        display: inline-flex;
                                                                        align-items: center;
                                                                        justify-content: center;
                                                                        width: 24px;
                                                                        height: 24px;
                                                                        border: 2px solid #ccc;
                                                                        border-radius: 4px;
                                                                        background-color: #fff;
                                                                        cursor: pointer;
                                                                        transition: all 0.2s ease;
                                                                    }

                                                                    .checkbox-btn.selected {
                                                                        border-color: #4caf50;
                                                                        background-color: #4caf50;
                                                                        color: #fff;
                                                                    }

                                                                    .checkbox-btn:hover {
                                                                        border-color: #999;
                                                                    }

                                                                    .checkbox-btn.selected:hover {
                                                                        background-color: #45a045;
                                                                        border-color: #45a045;
                                                                    }

                                                                    .checkbox-icon {
                                                                        font-size: 16px;
                                                                    }
                                                                </style>
                                                                <td>
                                                                    <span
                                                                        class="badge text-white {{ $conexionObjeto->estado == 1 ? 'bg-success' : 'bg-danger' }}">
                                                                        {{ $conexionObjeto->estado == 1 ? 'Seleccionado' : 'Deseleccionado' }}
                                                                    </span>
                                                                </td>

                                                                <td>{{ $conexionObjeto->proveedor_nombre }}</td>
                                                                <td><label>{{ $ProvedoresAsignados[$index]['tiempo_minimo_entrega'] }}</label>
                                                                </td>
                                                                <td><label>{{ $ProvedoresAsignados[$index]['tiempo_maximo_entrega'] }}</label>
                                                                </td>
                                                                <td><label>{{ $ProvedoresAsignados[$index]['precio_compra'] }}</label>
                                                                </td>
                                                                <td><label>{{ $ProvedoresAsignados[$index]['unidad'] }}</label>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="no-familias-seleccionadas w-100">
                                                    No hay provedores seleccionadas
                                                </div>
                                            @endif
                                        </div>
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
                                            <label for="stock" class="mr-2">Stock Acutal del Producto</label>
                                            <br>
                                            <label for="">{{ $itemEspecifico->stock }}</label>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="moc" class="mr-2">MOC (Minimo de venta a cliente)</label>

                                            <br>
                                            <label for="">{{ $itemEspecifico->moc }}</label>
                                        </div>
                                    </div>
                                    <label>El parametro por el que se hacen los calculos se basan en el proveedor
                                        seleccionado</label>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2 mb-3">
                                                <label for="cantidad_piezas_mayoreo" class="mr-2">Cant. Piezas
                                                    Mayoreo</label>
                                                <label
                                                    for="">{{ $itemEspecifico->cantidad_piezas_mayoreo }}</label>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="porcentaje_venta_mayorista" class="mr-2">% Venta
                                                    Mayorista</label>
                                                <label
                                                    for="">{{ $itemEspecifico->porcentaje_venta_mayorista }}</label>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="porcentaje_venta_minorista" class="mr-2">% Venta
                                                    Minorista</label>
                                                <label
                                                    for="">{{ $itemEspecifico->porcentaje_venta_minorista }}</label>
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
                                        <div>
                                            <a href="#" wire:click="editItem({{ $itemEspecifico->id }})"
                                                class="d-block mb-3" wire:click="">Editar item</a>

                                            <a href="#"
                                                class=" text-danger d-block mb-3"
                                                onclick="confirmDeletion({{ $itemEspecifico->id }}, '{{ $itemEspecifico->item->nombre }}'
                                                )">Eliminar</a>

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
</div>
