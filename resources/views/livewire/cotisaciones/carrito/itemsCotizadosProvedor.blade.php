<div>
    @if (count($itemsCotisacionProveedor) > 0)
        @foreach ($itemsCotisacionProveedor as $itemEspecifico)
            <div>
                <hr>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div>
                        <div class="card-body text-center d-flex justify-content-center align-items-center"
                            style="cursor: pointer;">
                            @if (empty($itemEspecifico->image))
                                <div class="no-image-icon"
                                    style="width: 200px; height: 200px; display: flex; justify-content: center; align-items: center; border: 1px solid #ddd; background-color: #f8f8f8;">
                                    📷 No hay imagen subida
                                </div>
                            @else
                                <img src="{{ asset('storage/' . explode(',', $itemEspecifico->image)[0]) }}"
                                    class="card-img-top" alt="{{ $itemEspecifico->item->nombre }}"
                                    style="width: 200px; height: 200px; object-fit: cover;"
                                    wire:click="viewItem({{ $itemEspecifico->id }})">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-8">

                    <h4>{{ $itemEspecifico->item->nombre }}</h4>
                    <label>{{ $itemEspecifico->item->descripcion }}</label>
                    <br>
                    <label>Unidad: {{ $itemEspecifico->unidad }}</label>
                    <br>
                    <label>Proveedor: {{ $itemEspecifico->nombreProveedor }}</label>
                    <br>
                    <label>Precio unitario: ${{ number_format($itemEspecifico->precio, 2) }}</label>
                    <br>
                    <label>Precio total:
                        @if ($itemEspecifico->cantidad)
                            ${{ number_format($itemEspecifico->precio * $itemEspecifico->cantidad, 2) }}
                        @else
                            ingresa un presio
                        @endif

                    </label>
                    <div class="row mt-3">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-danger btn-sm me-2"
                                    wire:click="actualizarCantidadProveedor({{ $itemEspecifico->id }}, -1)"
                                    @if ($itemEspecifico->cantidad <= 0) disabled @endif>
                                    -
                                </button>

                                <input type="number" min="0" class="form-control text-center"
                                    style="width: 100px;" wire:model="cantidades.{{ $itemEspecifico->id }}"
                                    wire:change="actualizarCantidadProveedor({{ $itemEspecifico->id }}, 0)">

                                <button class="btn btn-success btn-sm ms-2"
                                    wire:click="actualizarCantidadProveedor({{ $itemEspecifico->id }}, 1)">
                                    +
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-danger btn-sm"
                                wire:click="eliminarItemListaCoti({{ $itemEspecifico->id }})">
                                Eliminar
                            </button>
                        </div>
                        <div class="col-md-5">
                            <button class="btn btn-outline-primary btn-sm"
                                wire:click="viewItem({{ $itemEspecifico->id }})">
                                Cambiar Modalidad
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    @endif
</div>
