<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3">
    <link rel="stylesheet" href="{{ asset('css/crearClienteProyecto.css') }}">

    <style>
        .btn-icon {
            display: flex;
            align-items: center;
            background-color: transparent;
            color: #6c757d;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .btn-icon:hover {
            color: #5a6268;
        }

        .btn-icon i {
            margin-right: 5px;
        }

        .row.align-items-center {
            display: flex;
            align-items: center;
        }

        .ml-3 {
            margin-left: 1rem;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <h2 class="ml-3">Ordenes de venta</h2>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <!-- Input de búsqueda -->
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar lista..."
                        wire:model='searchTerm' wire:keydown='search'>
                    <!-- Filtro de Estado -->
                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2" wire:model="statusFiltro" wire:change="search">
                        <option value="">Estado</option>
                        <option value="0">Pendiente de pago</option>
                        <option value="1">Pagado</option>
                        <option value="2">Cancelado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2" wire:model="statusFiltro2" wire:change="search">
                        <option value="">Metodo de pago</option>
                        <option value="0">Deposito</option>
                        <option value="1">Efectivo</option>
                        <option value="2">Transferencia</option>
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
                                <th>Nombre</th>
                                <th>Cotisacion</th>
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

                                    <td>{{ $lista->nombre }}</td>
                                    <td>
                                        {{ $lista->cotizacion->nombre }}
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

                                        @if (!$esVistaFinanzas && ($lista->estado != 3 && $lista->estado != 1))
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="cancelar({{ $lista->id }})" title="Cancelar cotización">
                                                <i class="fas fa-times me-1"></i> Cancelar
                                            </button>
                                        @endif
                                        <!-- Para abrir la orden de venta -->
                                        <button class="btn btn-info btn-sm text-white"
                                            onclick="window.open('{{ route('proyecto.pdf-orden-venta', ['id' => $lista->id]) }}', '_blank')"
                                            title="Generar y ver el documento de la orden de venta">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </button>
                                        <button class="btn btn-info btn-sm"
                                            wire:click="viewOrden({{ $lista->id }})" title="Ver proyecto">
                                            <i class="fas fa-eye"></i>
                                        </button>
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
