<div class="form-group">
    <h4> Selecciona el provedor con el que cotizaras el item</h4>
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
                        <th>Precio de venta minorista</th>
                        <th>Precio de venta mayorista</th>
                        <th></th>

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
                                <button
                                    class="checkbox-btn {{ $conexionObjeto->estado == 1 ? 'selected' : '' }}"
                                    wire:click="seleccionarProveedor({{ $index }}, '{{ $conexionObjeto->proveedor_nombre }}')">
                                    @if ($conexionObjeto->estado == 1)
                                        <span class="checkbox-icon">✓</span>
                                    @endif
                                </button>

                            </td>
                            <td>{{ $conexionObjeto->proveedor_nombre }}</td>

                            <td><label>{{ $ProvedoresAsignados[$index]['tiempo_minimo_entrega'] }}</label>
                            </td>
                            <td><input step="1"
                                    class="form-control @error('ProvedoresAsignados.' . $index . '.tiempo_maximo_entrega') is-invalid @enderror"
                                    wire:model.lazy="ProvedoresAsignados.{{ $index }}.tiempo_maximo_entrega"
                                    oninput="validateNumberOnly(this)">
                            </td>
                            <td><input step="0.01"
                                    class="form-control @error('ProvedoresAsignados.' . $index . '.precio_compra') is-invalid @enderror"
                                    wire:model.lazy="ProvedoresAsignados.{{ $index }}.precio_compra"
                                    wire:keydown='handleKeydown({{ $index }})'
                                    oninput="validatePrice(this)">
                            </td>
                            <td><input step="0.01"
                                    class="form-control @error('ProvedoresAsignados.' . $index . '.unidad') is-invalid @enderror"
                                    wire:model.lazy="ProvedoresAsignados.{{ $index }}.unidad"
                                    wire:keydown='handleKeydownUnidad({{ $index }})'>
                            </td>
                            <td><input step="0.01"
                                class="form-control @error('ProvedoresAsignados.' . $index . '.unidad') is-invalid @enderror"
                                wire:model.lazy="ProvedoresAsignados.{{ $index }}.unidad"
                                wire:keydown='handleKeydownUnidad({{ $index }})'>
                        </td>
                        <td><input step="0.01"
                            class="form-control @error('ProvedoresAsignados.' . $index . '.unidad') is-invalid @enderror"
                            wire:model.lazy="ProvedoresAsignados.{{ $index }}.unidad"
                            wire:keydown='handleKeydownUnidad({{ $index }})'>
                    </td>
                            <td><button
                                    wire:click="eliminarProveedor({{ $index }})"
                                    class="btn btn-danger">Eliminar</button>
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
    <button href="#" wire:click="montarModalProveedores()"
        class="btn btn-secondary mt-3">Agregar provedor</button>
</div>