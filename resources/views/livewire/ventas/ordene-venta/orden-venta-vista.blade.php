<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <h2 class="ml-3">Ordenes de venta</h2>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-10">
                    <!-- Input de búsqueda -->
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar lista..."
                        wire:model='searchTerm' wire:keydown='search'>
                    <!-- Filtro de Estado -->
                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2" wire:model="statusFiltro" wire:change="search">
                        <option value="0">Preferencia</option>
                        <option value="1">Tiempo de entrega</option>
                        <option value="2">Precio</option>
                    </select>
                </div>
            </div>
            @if ($ordenesVenta && $ordenesVenta->count() > 0)

                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                @if (Auth::user()->hasRole('Administrador'))
                                    <th>Usuario</th>
                                @endif
                                <th>Cliente</th>
                                <th>Lista</th>
                                <th>Forma de pago</th>
                                <th>Metodo de pago</th>
                                <th>Monto Total</th>
                                <th>Monto por pagar</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordenesVenta as $lista)
                                <tr>
                                    @if (Auth::user()->hasRole('Administrador'))
                                        <td>
                                            {{ $lista->usuario->name ?? 'Sin asignar' }}
                                            {{ $lista->usuario->first_last_name ?? 'Sin asignar' }}
                                            {{ $lista->usuario->second_last_name ?? 'Sin asignar' }}
                                        </td>
                                    @endif

                                    <td>{{ $lista->cliente->nombre }}</td>
                                    <td>
                                        Cotisacion {{ $lista->cotizacion->nombre }}
                                    </td>
                                    <td>
                                        <label>
                                            {!! $lista->formaPago == 1
                                                ? '<span class="badge badge-warning">Parcial</span>'
                                                : ($lista->formaPago == 2
                                                    ? '<span class="badge badge-warning">Total</span>'
                                                    : '<span class="badge badge-secondary">Estado desconocido</span>') !!}
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            {!! $lista->metodoPago == 1
                                                ? '<span class="badge badge-primary">Deposito</span>'
                                                : ($lista->metodoPago == 2
                                                    ? '<span class="badge badge-primary">Efectivo</span>'
                                                    : ($lista->metodoPago == 3
                                                        ? '<span class="badge badge-primary">Transferencia</span>'
                                                        : '<span class="badge badge-secondary">Estado desconocido</span>')) !!}
                                        </label>
                                    </td>
                                    <td style="text-align: right; font-weight: bold; color: green;">
                                        {{ number_format($lista->monto, 2) }} MXN
                                    </td>
                                    <td style="text-align: right; font-weight: bold; color: rgb(207, 0, 0);">
                                        {{ number_format($lista->montoPagar, 2) }} MXN
                                    </td>

                                    <td>
                                        <label>
                                            {!! $lista->estado == 0
                                                ? '<span class="badge badge-warning">Pendiente de pago</span>'
                                                : ($lista->estado == 1
                                                    ? '<span class="badge badge-success">Pago completado</span>'
                                                    : ($lista->estado == 2
                                                        ? '<span class="badge badge-warning">Pago parcial</span>'
                                                        : ($lista->estado == 3
                                                            ? '<span class="badge badge-danger">Cancelada</span>'
                                                            : '<span class="badge badge-secondary">Estado desconocido</span>'))) !!}
                                        </label>
                                    </td>
                                    <td>
                                        @if ($esVistaFinanzas && $lista->estado == 0)
                                            <button class="btn btn-primary btn-sm"
                                                wire:click="abrirModalPagar({{ $lista->id }})" title="Pagar">
                                                Pagar
                                            </button>
                                        @endif




                                        @if (!$esVistaFinanzas  && ($lista->estado == 0 || $lista->estado == 1))
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="cancelar({{ $lista->id }})" title="Cancelar cotización">
                                                <i class="fas fa-times me-1"></i> Cancelar
                                            </button>
                                        @endif
                                        <!-- Botón de Cancelar (existente) -->
                                        <button class="btn btn-info btn-sm text-white"
                                            wire:click="generarPDF({{ $lista->id }})"
                                            title="Generar PDF de la cotización">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </button>
                                        <!-- Botón para Ver Proyecto -->

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $ordenesVenta->links() }}
                </div>
            @else
                <div>
                    No tienes listas para cotizar.
                </div>
            @endif
            <!-- Enlace de paginación -->

        </div>
    </div>
    @include('livewire.ventas.ordene-venta.modalParaPagar')



</div>
