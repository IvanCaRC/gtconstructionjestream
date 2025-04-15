<div class="form-group">
    @if ($tipoCotisacion == null)
        <h4>
            Seleciona el tipo de cotisacion que realizaras
        </h4>
        <button href="#" wire:click="cambiarStock()" class="btn btn-primary mt-3">Cotisacion de stock</button>
        <button href="#" wire:click="cambiarProveedor()" class="btn btn-primary mt-3">Cotizacion de
            proveedor</button>
    @else
        @if ($tipoCotisacion == 1)
            <h4>
                Cambiar tipo de cotizacion
            </h4>
            <button href="#" wire:click="cambiarStock()" class="btn btn-primary mt-3">Cotisacion de stock</button>
        @endif
        @if ($tipoCotisacion == 2)
            <h4>
                Cambiar tipo de cotizacion
            </h4>
            <button href="#" wire:click="cambiarProveedor()" class="btn btn-primary mt-3">Cotizacion de
                proveedor</button>
        @endif
    @endif
    @if ($tipoCotisacion == 1)
        <div class="py-4">
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
                                            class="checkbox-btn {{ $proveedorSeleccionadoId === $ProvedoresAsignados[$index]['proveedor_id'] ? 'selected' : '' }}"
                                            wire:click="seleccionarProveedor({{ $ProvedoresAsignados[$index]['proveedor_id'] }})">
                                            @if ($proveedorSeleccionadoId === $ProvedoresAsignados[$index]['proveedor_id'])
                                                <span class="checkbox-icon">✓</span>
                                            @endif
                                        </button>
                                    </td>


                                    <td>{{ $conexionObjeto->proveedor_nombre }}</td>

                                    <td><label>{{ $ProvedoresAsignados[$index]['tiempo_minimo_entrega'] }}</label>
                                    </td>
                                    <td>
                                        <label>{{ $ProvedoresAsignados[$index]['tiempo_maximo_entrega'] }}</label>
                                    </td>
                                    <td>
                                        <label>{{ $ProvedoresAsignados[$index]['precio_compra'] }}</label>
                                    </td>
                                    <td>
                                        <label>{{ $ProvedoresAsignados[$index]['unidad'] }}</label>
                                    </td>
                                    <td>
                                        <label>
                                            {{ number_format($ProvedoresAsignados[$index]['precio_compra'] + $ProvedoresAsignados[$index]['precio_compra'] * ($itemEspecifico->porcentaje_venta_mayorista / 100), 2) }}
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            {{ number_format($ProvedoresAsignados[$index]['precio_compra'] + $ProvedoresAsignados[$index]['precio_compra'] * ($itemEspecifico->porcentaje_venta_minorista / 100), 2) }}
                                        </label>
                                    </td>
                                    <td>
                                        SUGERENCIA
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        @if ($proveedorSeleccionadoId != null)
                            <div class="form-group">
                                <div class="d-flex justify-content-center align-items-center mt-2">
                                    <!-- Botón de menos -->
                                    <button class="btn btn-danger btn-sm me-2"
                                       >-</button>

                                    <!-- Input de cantidad -->
                                    <input type="number" min="1" class="form-control text-center"
                                        style="width: 60px;" >

                                    <!-- Botón de más -->
                                    <button class="btn btn-success btn-sm ms-2"
                                        >+</button>
                                </div>
                                <br>
                                <div>
                                    <button class="btn btn-success btn-custom"
                                       
                                        title="Agrega este item a tu lista">
                                        <i class="fas fa-shopping-cart"></i> Añadir a la lista
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="no-familias-seleccionadas w-100">
                        No hay provedores seleccionadas
                    </div>
                @endif

            </div>

        </div>
    @endif
    @if ($tipoCotisacion == 2)
    <div class="form-group">
        <div class="d-flex justify-content-center align-items-center mt-2">
            <!-- Botón de menos -->
            <button class="btn btn-danger btn-sm me-2"
               >-</button>

            <!-- Input de cantidad -->
            <input type="number" min="1" class="form-control text-center"
                style="width: 60px;" >

            <!-- Botón de más -->
            <button class="btn btn-success btn-sm ms-2"
                >+</button>
        </div>
        <br>
        <div>
            <button class="btn btn-success btn-custom"
               
                title="Agrega este item a tu lista">
                <i class="fas fa-shopping-cart"></i> Añadir a la lista
            </button>
        </div>
    </div>
    @endif
</div>
