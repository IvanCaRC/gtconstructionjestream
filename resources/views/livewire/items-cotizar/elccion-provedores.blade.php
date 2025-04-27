<div>

    @if (
        (in_array($itemEspecifico->id, $itemsEnLista) || in_array($itemEspecifico->id, $itemsEnListaProveedores)) &&
            $cambioModalida == false)
        <h4>El item ya se encuentra en la cotisacion</h4>
        <button class="btn btn-warning btn-custom" title="Este item ya está en tu lista" wire:click="cambioDeModalidad">
            <i class="fas fa-shopping-cart"></i> Cambiar modalidad
        </button>
        <button class="btn btn-primary btn-custom" title="Este item ya está en tu lista"
            wire:click="verLista({{ $idCotizaciones }})">
            <i class="fas fa-shopping-cart"></i> Ver en lista
        </button>
    @elseif($cambioModalida == false)
        <div class="form-group">

            @if ($tipoCotizacion == null)
                <h4>
                    Seleciona el tipo de cotisacion que realizaras
                </h4>
                <button href="#" wire:click="cambiarStock()" class="btn btn-primary mt-3">Cotisacion de
                    stock</button>
                <button href="#" wire:click="cambiarProveedor()" class="btn btn-primary mt-3">Cotizacion de
                    proveedor</button>
                <br>
            @else
                @if ($tipoCotizacion == 1)
                    <h4>
                        Cambiar tipo de cotizacion
                    </h4>
                    <button href="#" wire:click="cambiarStock()" class="btn btn-primary mt-3">Cotisacion de
                        stock</button>
                @endif
                @if ($tipoCotizacion == 2)
                    <h4>
                        Cambiar tipo de cotizacion
                    </h4>
                    <button href="#" wire:click="cambiarProveedor()" class="btn btn-primary mt-3">Cotizacion de
                        proveedor</button>
                @endif
            @endif
            @if ($tipoCotizacion == 1)
                @include('livewire.items-cotizar.tipoCotizacion1')
            @endif
            @if ($tipoCotizacion == 2)
                @include('livewire.items-cotizar.tipoCotizacion2')
            @endif
        </div>
    @elseif($cambioModalida == true)
        <div class="form-group">

            @if ($tipoCotizacion == null)
                <h4>
                    Seleciona el tipo de cotisacion que realizaras
                </h4>
                <button href="#" wire:click="cambiarStock()" class="btn btn-primary mt-3">Cotisacion de
                    stock</button>
                <button href="#" wire:click="cambiarProveedor()" class="btn btn-primary mt-3">Cotizacion de
                    proveedor</button>
                <br>
            @else
                @if ($tipoCotizacion == 1)
                    <h4>
                        Cambiar tipo de cotizacion
                    </h4>
                    <button href="#" wire:click="cambiarStock()" class="btn btn-primary mt-3">Cotisacion de
                        stock</button>
                @endif
                @if ($tipoCotizacion == 2)
                    <h4>
                        Cambiar tipo de cotizacion
                    </h4>
                    <button href="#" wire:click="cambiarProveedor()" class="btn btn-primary mt-3">Cotizacion de
                        proveedor</button>
                @endif
            @endif
            @if ($tipoCotizacion == 1)
                <div class="py-4">
                    <h4 class="mb-3 fw-semibold">Selecciona el proveedor con el que cotizarás el item</h4>
                    @if (!$preferenciaProyecto)
                        <p>No hay una preferencia de proyecto selecionada</p>
                    @endif
                    @if ($preferenciaProyecto == 1)
                        <p>Preferencia de precios calculada en base al tiempo de entrega</p>
                    @endif
                    @if ($preferenciaProyecto == 2)
                        <p>Preferencia de proyecto calculada en base al precio</p>
                    @endif
                    @if (count($proveedoresAsignados) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Mín. Entrega</th>
                                        <th>Máx. Entrega</th>
                                        <th>Precio Compra</th>
                                        <th>Unidad</th>
                                        <th>Venta Minorista</th>
                                        <th>Venta Mayorista</th>
                                        @if ($preferenciaProyecto)
                                            <th>Recomendado</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($proveedoresAsignados as $index => $conexion)
                                        @php
                                            $conexionObjeto = (object) $conexion;
                                        @endphp
                                        <tr>
                                            <td>
                                                <button
                                                    class="checkbox-btn {{ $proveedorSeleccionadoId === $conexion['proveedor_id'] ? 'selected' : '' }}"
                                                    wire:click="seleccionarProveedor({{ $conexion['proveedor_id'] }})">
                                                    @if ($proveedorSeleccionadoId === $conexion['proveedor_id'])
                                                        <span class="checkbox-icon">✓</span>
                                                    @endif
                                                </button>
                                            </td>
                                            <td wire:loading.delay.class="text-muted"
                                                wire:target="seleccionarProveedor">
                                                {{ $conexionObjeto->proveedor_nombre }}
                                            </td>
                                            <td>{{ $conexion['tiempo_minimo_entrega'] }}</td>
                                            <td>{{ $conexion['tiempo_maximo_entrega'] }}</td>
                                            <td>${{ number_format($conexion['precio_compra'], 2) }}</td>
                                            <td>{{ $conexion['unidad'] }}</td>
                                            <td>${{ number_format($conexion['precio_compra'] * (1 + $itemEspecifico->porcentaje_venta_minorista / 100), 2) }}
                                            </td>
                                            <td>${{ number_format($conexion['precio_compra'] * (1 + $itemEspecifico->porcentaje_venta_mayorista / 100), 2) }}
                                            </td>
                                            @if ($preferenciaProyecto)
                                                <td>
                                                    @if ($conexion['proveedor_id'] === $proveedorRecomendadoId)
                                                        <span class="badge bg-success">✓ Recomendado</span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mostrar controles si hay proveedor seleccionado --}}
                        @if ($proveedorSeleccionadoId)
                            <div class="form-group mt-3">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button class="btn btn-danger btn-sm me-2" wire:click="decrementarCantidad"
                                        wire:loading.attr="disabled">-</button>

                                    <input type="number" min="1" class="form-control text-center"
                                        style="width: 60px;" wire:model="cantidad">

                                    <button class="btn btn-success btn-sm ms-2" wire:click="incrementarCantidad"
                                        wire:loading.attr="disabled">+</button>
                                </div>
                                <div class="text-center mt-3">
                                    <button class="btn btn-success btn-custom" title="Agrega este item a tu lista"
                                        wire:click="agregarItemProveedorLista('{{ $itemEspecifico->id }}|{{ $proveedorSeleccionadoId }}|{{ $item->nombre }}')"
                                        wire:loading.attr="disabled" wire:target="agregarItemProveedorLista">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span wire:loading.remove>Añadir a la lista</span>
                                        <span wire:loading>Procesando...</span>
                                    </button>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning text-center mt-4">
                            No hay proveedores asignados.
                        </div>
                    @endif

                    <style>
                        .precio-destacado {
                            font-weight: bold;
                            color: #2e7d32;
                        }

                        .checkbox-btn {
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            width: 26px;
                            height: 26px;
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

                        .checkbox-icon {
                            font-size: 16px;
                        }
                    </style>
                </div>

            @endif
            @if ($tipoCotizacion == 2)
                <div class="form-group">
                    <div class="d-flex justify-content-center align-items-center mt-2">
                        <!-- Botón de menos -->
                        <button class="btn btn-danger btn-sm me-2" wire:click="decrementarCantidad">-</button>

                        <!-- Input de cantidad -->
                        <input type="text" min="1" class="form-control text-center" style="width: 90px;"
                            wire:model="cantidad" oninput="validatePhoneInput(this)">
                        <!-- Botón de más -->
                        <button class="btn btn-success btn-sm ms-2" wire:click="incrementarCantidad">+</button>
                    </div>
                    <br>
                    <div>
                        <button class="btn btn-success btn-custom"
                            wire:click="agregarItemStockLista('{{ $itemEspecifico->id }}|{{ $item->nombre }}')"
                            title="Agrega este item a tu lista">
                            <i class="fas fa-shopping-cart"></i> Añadir a la lista
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
