<div>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <div class="flex h-screen gap-4 p-4">
            <!-- Primera sección (85%) -->
            <div class="flex-1 bg-white p-4 rounded-lg border border-black" style="flex: 0 0 80%;">
                <div class="row">
                    <div class="col-md-9">
                        <h3>Items de la lista</h3>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-secondary " wire:click="$set('openModalItemPersonalisado', true)">
                            Agregar item personalizado
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if (count($itemsDeLaLista) > 0)
                        @foreach ($itemsDeLaLista as $itemEspecifico)
                            <div>
                                <hr>
                            </div>

                            <div class="row">


                                <div class="col-md-3">
                                    <div>
                                        <div class="card-body text-center d-flex justify-content-center align-items-center"
                                            style="cursor: pointer;">
                                            <!-- Imagen del Item -->
                                            @if (empty($itemEspecifico->image))
                                                <!-- Mostrar ícono o mensaje alternativo -->
                                                <div class="no-image-icon"
                                                    style="width: 200px; height: 200px; display: flex; justify-content: center; align-items: center; border: 1px solid #ddd; background-color: #f8f8f8;">
                                                    📷 No hay imagen subida
                                                </div>
                                            @else
                                                <!-- Mostrar imagen -->
                                                <img src="{{ asset('storage/' . explode(',', $itemEspecifico->image)[0]) }}"
                                                    class="card-img-top" alt="{{ $itemEspecifico->item->nombre }}"
                                                    style="width: 200px; height: 200px; object-fit: cover;"
                                                    wire:click="viewItem({{ $itemEspecifico->id }})">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <h4>{{ $itemEspecifico->item->nombre }}</h4>
                                    <label>{{ $itemEspecifico->item->descripcion }}</label>
                                    <br>
                                    <label>Unidad: {{ $itemEspecifico->unidad }}</label>
                                    <br>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <!-- Botón de menos -->
                                                <button class="btn btn-danger btn-sm me-2"
                                                    wire:click="actualizarCantidad({{ $itemEspecifico->id }}, -1)">-</button>

                                                <!-- Input de cantidad -->
                                                <input type="number" min="0" class="form-control text-center"
                                                    style="width: 100px;"
                                                    wire:model.defer="cantidades.{{ $itemEspecifico->id }}"
                                                    wire:change="actualizarCantidad({{ $itemEspecifico->id }}, 0)">


                                                <!-- Botón de más -->
                                                <button class="btn btn-success btn-sm ms-2"
                                                    wire:click="actualizarCantidad({{ $itemEspecifico->id }}, 1)">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="#" class="d-block text-danger"
                                                wire:click.prevent="eliminarItemLista({{ $itemEspecifico->id }})">
                                                Eliminar
                                            </a>

                                        </div>

                                        <div class="col-md-2">
                                            <a href="#" wire:click="viewItem({{ $itemEspecifico->id }})"
                                                class="d-block text-default">Ver item</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class='px-6 py-2'>
                            <p>No hay items en la lista</p>
                        </div>
                    @endif
                    @if (count($itemsTemporalesDeLaLista) > 0)
                        <h4>Item Personalizados solicitados</h4>
                        @foreach ($itemsTemporalesDeLaLista as $itemEspecifico)
                            <div>
                                <hr>
                            </div>

                            <div class="row">
                                <div class="col-md-9">
                                    <h4>{{ $itemEspecifico->item->nombre }}</h4>
                                    <label>{{ $itemEspecifico->item->descripcion }}</label>
                                    <br>
                                    <label>Unidad: {{ $itemEspecifico->unidad }}</label>
                                    <br>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="d-flex align-items-center">
                                                <!-- Botón de menos -->
                                                <button class="btn btn-danger btn-sm me-2"
                                                    wire:click="actualizarCantidadTemporal({{ $itemEspecifico->id }}, -1)">-</button>

                                                <!-- Input de cantidad -->
                                                <input type="number" min="0" class="form-control text-center"
                                                    style="width: 60px;"
                                                    wire:model.defer="cantidades.{{ $itemEspecifico->id }}"
                                                    wire:change="actualizarCantidadTemporal({{ $itemEspecifico->id }}, 0)">


                                                <!-- Botón de más -->
                                                <button class="btn btn-success btn-sm ms-2"
                                                    wire:click="actualizarCantidadTemporal({{ $itemEspecifico->id }}, 1)">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="#" class="d-block text-danger"
                                                wire:click.prevent="eliminarItemTempoLista({{ $itemEspecifico->id }})">
                                                Eliminar
                                            </a>

                                        </div>


                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class='px-6 py-2'>
                            <p>No hay items temporales en la lista</p>
                        </div>
                    @endif
                </div>
                <div>

                </div>

            </div>
            <!-- Segunda sección (15%) -->
            <div class="bg-white rounded-lg border border-black p-4" style="flex: 0 0 20%;">
                <div class="card-body">
                    <h4>Resumen</h4>
                    <hr>
                    <label for="">Items: {{ $cantidadItemsDiferentes }}</label>

                    <label for="">Items Personalizados: {{ $cantidadItemsTemporaesDiferentes }}. </label>
                    <br>
                    <label for="">Total:
                        {{ $cantidadItemsDiferentes + $cantidadItemsTemporaesDiferentes }}</label>
                </div>
                <div>
                    @if ($listadeUsuarioActiva == null)
                        <div class="col-md-12">
                            <h4 class="px-5">
                                Error
                            </h4>
                        </div>
                    @else
                        @if ($nombreCliente == 'Sin cliente')
                            <div>
                                <button class="btn btn-primary" wire:click="abrirModalAsignarLista">
                                    Agregar lista aproyecto
                                </button>

                            </div>
                            <div class="py-3">
                                <button class="btn btn-danger" wire:click="desactivarLista({{ $idListaActual }})">
                                    Desactivar
                                </button>
                            </div>
                        @else
                            <div class="card-body">

                                <label>
                                    Preferencia:
                                    {{ $preferenciaProeycto == 1 ? 'Tiempo de entrega' : ($preferenciaProeycto == 2 ? 'Precio' : 'Sin preferencia') }}
                                </label>
                                <div>
                                    <div class="py-3">
                                        @php
                                            $totalItems = count($itemsDeLaLista) + count($itemsTemporalesDeLaLista);
                                        @endphp

                                        <button class="btn btn-primary"
                                            onclick="enviar({{ $idProyectoActual }}, {{ $totalItems }})">
                                            Enviar a cotización
                                        </button>

                                    </div>
                                    <div class="py-3">
                                        <button class="btn btn-danger"
                                            wire:click="desactivarLista({{ $idListaActual }})">
                                            Desactivar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif


                    @endif

                </div>
            </div>
        </div>
    </div>
    @include('livewire.cliente.modalItemPersonalisado.modalItemPersonalisado')
    @include('livewire.cliente.modalEleccionLista.modalEleccionCLieteProyectoLista')

    <script>
        function enviar(idProyecto, totalItems) {
            if (totalItems == 0) {
                Swal.fire('Error', 'No puedes enviar una lista vacía', 'error');
                return;
            }

            Swal.fire({
                title: 'Confirmar Envío',
                html: '¿Deseas enviar la lista a cotizar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('enviarListaCotizar', idProyecto)
                        .then(result => {
                            if (result) {
                                Swal.fire({
                                    title: 'Envío exitoso',
                                    text: 'La lista se envió correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Solución 1: Redirección con parámetro dinámico
                                    window.location.href =
                                        `/ventas/clientes/vistaEspecProyecto/${idProyecto}`;
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Ocurrió un problema al enviar la lista', 'error');
                            console.error(error);
                        });
                }
            });
        }
    </script>


</div>
