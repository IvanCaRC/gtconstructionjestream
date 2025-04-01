<div>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="flex h-screen gap-4 p-4">
            <!-- Primera sección (85%) -->
            <div class="flex-1 bg-white p-4 rounded-lg border border-black" style="flex: 0 0 80%;">
                <div class="row">
                    <div class="col-md-9">
                        <h3>Items de la lista</h3>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-secondary "  wire:click="$set('openModalItemPersonalisado', true)">
                            Agregar item personalisado
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
                                        <div class="col-md-2">
                                            <div class="d-flex align-items-center">
                                                <!-- Botón de menos -->
                                                <button class="btn btn-danger btn-sm me-2"
                                                    wire:click="actualizarCantidad({{ $itemEspecifico->id }}, -1)">-</button>

                                                <!-- Input de cantidad -->
                                                <input type="number" min="0" class="form-control text-center"
                                                    style="width: 60px;"
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
                </div>
                <div>
                    
                </div>

            </div>
            <!-- Segunda sección (15%) -->
            <div class="bg-white rounded-lg border border-black p-4" style="flex: 0 0 20%;">
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
    @include('livewire.cliente.modalItemPersonalisado.modalItemPersonalisado')
</div>
