<div>
    <h2 class="ml-3">Proyectos del cliente</h2>
    <div class="card">
        <div class="card-body">
            <div class="text-left mb-3">
                <button class="btn btn-custom" style="background-color: #4c72de; color: white;"
                    wire:click="$set('openModalCreacionProyecto', true)">Registrar nuevo proyecto</button>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <!-- Input de búsqueda -->
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar proyecto..."
                        wire:model='searchTerm' wire:keydown='search'>

                    <!-- Filtro de Estado -->

                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2" wire:model="statusFiltro" wire:change="search">
                        <option value="0">Todos los estados</option>
                        <option value="1">Activo</option>
                        <option value="2">Inactivo</option>
                        <option value="3">Cancelados</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2" wire:model="statusProcesos" wire:change="filter">
                        <option value="6">Todos los procesos</option>
                        <option value="0">Creando lista a cotizar</option>
                        <option value="1">Creando cotización</option>
                        <option value="2">Cotizado</option>
                        <option value="3">En proceso de venta</option>
                        <option value="4">Venta terminada</option>
                        <option value="5">Cancelada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2" wire:model="statusTipos" wire:change="filter">
                        <option value="3">Todos los tipos</option>
                        <option value="2">Cancelados</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            @if ($proyectos && $proyectos->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Proceso</th>
                            <th>Tipo</th>
                            <th>Dirección</th>
                            <th>Listas</th>
                            <th>Cotizaciones</th>
                            <th>Órdenes</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proyectos as $proyecto)
                            <tr
                                style="
                                {{ $proyecto->estado === 3 ? 'background-color: #ffbbbb;' : '' }}
                                {{ $proyecto->estado === 4 ? 'background-color: #bbffbb;' : '' }}">
                                <td>{{ $proyecto->nombre }}</td>
                                <td>
                                    {{ $proyecto->fecha }}
                                </td>
                                <td>
                                    {!! $proyecto->estado == 1
                                        ? '<span class="badge badge-success">Activo</span>'
                                        : ($proyecto->estado == 2
                                            ? '<span class="badge badge-warning">Inactivo</span>'
                                            : ($proyecto->estado == 3
                                                ? '<span class="badge" style="background-color: #f10808; color: white;">Cancelado</span>'
                                                : ($proyecto->estado == 4
                                                    ? '<span class="badge" style="background-color: #0bd20b; color: rgb(255, 255, 255);">Concluido</span>'
                                                    : '<span class="badge badge-secondary">Estado desconocido</span>'))) !!}
                                </td>
                                <td>
                                    <label>
                                        {!! $proyecto->proceso == 0
                                            ? '<span class="badge badge-primary">Creando lista a cotizar</span>'
                                            : ($proyecto->proceso == 1
                                                ? '<span class="badge badge-primary">Creando cotización</span>'
                                                : ($proyecto->proceso == 2
                                                    ? '<span class="badge badge-primary">Cotizado</span>'
                                                    : ($proyecto->proceso == 3
                                                        ? '<span class="badge badge-warning">Esperando pago</span>'
                                                        : ($proyecto->proceso == 4
                                                            ? '<span class="badge badge-primary">Pagado/span>'
                                                            : ($proyecto->proceso == 5
                                                                ? '<span class="badge badge-warning">Preparando</span>'
                                                                : ($proyecto->proceso == 6
                                                                    ? '<span class="badge badge-warning">En proceso de entrga</span>'
                                                                    : ($proyecto->proceso == 7
                                                                        ? '<span class="badge badge-success">Venta terminada</span>'
                                                                        : ($proyecto->proceso == 8
                                                                            ? '<span class="badge badge-danger">Cancelado</span>'
                                                                            : '<span class="badge badge-secondary">Estado desconocido</span>')))))))) !!}
                                    </label>
                                </td>
                                <td>
                                    {!! $proyecto->tipo == 0
                                        ? '<span class="badge badge-secondary">Obra</span>'
                                        : ($proyecto->tipo == 1
                                            ? '<span class="badge badge-secondary">Suministro</span>'
                                            : '<span class="badge badge-secondary">Estado desconocido</span>') !!}
                                </td>
                                <td>
                                    @if ($proyecto->direccion)
                                        {{ $proyecto->direccion->calle }}, {{ $proyecto->direccion->numero }},
                                        {{ $proyecto->direccion->colonia }}, {{ $proyecto->direccion->ciudad }},
                                        {{ $proyecto->direccion->estado }}, {{ $proyecto->direccion->pais }}, C.P.
                                        {{ $proyecto->direccion->cp }}
                                    @else
                                        Sin dirección
                                    @endif
                                </td>
                                <td>{{ $proyecto->listas }}</td>
                                <td>{{ $proyecto->cotisaciones }}</td>
                                <td>{{ $proyecto->ordenes }}</td>
                                <td>
                                    <button class="btn btn-info btn-custom"
                                        wire:click="viewProyecto({{ $proyecto->id }})" title="Ver proyecto">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                @if (is_null($proyecto->culminacion))
                                    <td>
                                        <button class="btn btn-primary btn-custom"
                                            wire:click="cargarDatosProyecto({{ $proyecto->id }})" title="Modificar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-custom"
                                            wire:click="solicitarCancelacion({{ $proyecto->id }})"
                                            title="Cancelar proyecto">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-custom"
                                            wire:click="culminarProyecto({{ $proyecto->id }})"
                                            title="Terminar proyecto exitosamente (tener al menos una lista concluida)"
                                            @if ($proyecto->listasCotizar->where('estado', 8)->isEmpty()) disabled @endif>
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </td>
                                    <td></td> {{-- Espacio reservado para alineación --}}
                                @elseif($proyecto->culminacion === 0 && $proyecto->estado === 2)
                                    <td colspan="2"></td> {{-- Espacio vacío para mantener alineación --}}
                                    <td>
                                        <span class="badge bg-warning text-dark">Cancelación pendiente</span>
                                    </td>
                                @elseif($proyecto->culminacion === 1 && $proyecto->estado === 2)
                                    <td colspan="2"></td> {{-- Espacio vacío para mantener alineación --}}
                                    <td>
                                        <span class="badge bg-success text-white">Culminación pendiente</span>
                                    </td>
                                @elseif ($proyecto->culminacion === 0 && $proyecto->estado === 3)
                                    <td colspan="2"></td> {{-- Espacio vacío para mantener alineación --}}
                                    <td>
                                        <span class="badge" style="background-color: #f10808; color: white;">Proyecto
                                            Cancelado</span>
                                    </td>
                                @elseif ($proyecto->culminacion === 1 && $proyecto->estado === 4)
                                    <td colspan="2"></td> {{-- Espacio vacío para mantener alineación --}}
                                    <td>
                                        <span class="badge" style="background-color: #49c80e; color: white;">Proyecto
                                            Concretado</span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $proyectos->links() }}
            @else
                <div>
                    No hay proyectos registrados para este cliente.
                </div>
            @endif
        </div>

    </div>
</div>
