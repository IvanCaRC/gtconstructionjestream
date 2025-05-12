<div>
    @include('livewire.cliente.modal-respuesta-cancelacion-proyecto')
    @include('livewire.cliente.modal-respuesta-culminacion-proyecto')
    @if ($mensajeError)
        <div class="alert alert-danger">
            {{ $mensajeError }}
        </div>
    @endif
    <h1 class="pl-4">Culminaciones y cancelaciones de proyectos</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-12">
        <div class="card">
            <div class="card-body">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <div class="table-responsive">

                    <div class="d-flex justify-content-between mb-3">
                        <!-- Input de búsqueda -->
                        <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm'
                            wire:keydown='search' placeholder="Buscar proyecto...">

                        <!-- Filtro de Estado -->
                        <select class="form-control mr-2" wire:model="statusFiltroDeBusqueda" wire:change="filter">
                            <option value="0">Todos los estados</option>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                            <option value="3">Cancelado</option>
                        </select>

                        <!-- Filtro de tipo de solicitud -->
                        <select class="form-control mr-2" wire:model="tipoFiltroDeBusqueda" wire:change="filter">
                            <option value="0">Todos los informes</option>
                            <option value="1">Informa Cancelacion</option>
                            <option value="2">Informa Culminacion</option>
                        </select>
                    </div>

                    @if ($proyectos->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Solicita</th>
                                    <th>Nombre del Proyecto</th>
                                    <th>Estado del proyecto</th>
                                    <th>Cliente</th>
                                    <th>Tipo de Proyecto</th>
                                    <th>Proceso</th>
                                    <th>Usuario Asignado</th>
                                    <th>Fecha de registro</th>
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
                                        <td>
                                            @if ($proyecto->culminacion == 0)
                                                <span class="badge"
                                                    style="background-color: #c00000; color: white;">Cancelacion</span>
                                            @elseif ($proyecto->culminacion == 1)
                                                <span class="badge"
                                                    style="background-color: #25ba48; color: white;">Culminacion</span>
                                            @else
                                                <span class="badge badge-danger">Sin asignar</span>
                                            @endif
                                        </td>
                                        <td>{{ $proyecto->nombre }}</td>
                                        <td>
                                            @if ($proyecto->estado == 1)
                                                <span class="badge"
                                                    style="background-color: #34ff63; color: white;">Activo</span>
                                            @elseif ($proyecto->estado == 2)
                                                <span class="badge"
                                                    style="background-color: #db8012; color: white;">Inactivo</span>
                                            @elseif ($proyecto->estado == 3)
                                                <span class="badge"
                                                    style="background-color: #fb0909; color: white;">Cancelado</span>
                                            @else
                                                <span class="badge badge-danger">Sin asignar</span>
                                            @endif
                                        </td>
                                        <td>{{ $proyecto->cliente->nombre ?? 'Sin asignar' }}</td>
                                        {{-- 0 Obra 1 Suministro --}}
                                        <td>
                                            @if ($proyecto->tipo == 0)
                                                <span class="badge"
                                                    style="background-color: #524949; color: white;">Obra</span>
                                            @elseif ($proyecto->tipo == 1)
                                                <span class="badge"
                                                    style="background-color: #fd43149c; color: white;">Suministro</span>
                                            @else
                                                <span class="badge badge-danger">Sin asignar</span>
                                            @endif
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>{{ $proyecto->cliente->user->name ?? '' }}
                                            {{ $proyecto->cliente->user->first_last_name ?? '' }}
                                            {{ $proyecto->cliente->user->second_last_name ?? '' }}</td>
                                        <td>{{ $proyecto->fecha }}</td>
                                        <td>
                                            <button class="btn btn-info btn-custom"
                                                wire:click="viewProyecto({{ $proyecto->id }})" title="Ver proyecto">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        @if ($proyecto->estado === 2)
                                            <td>
                                                <button class="btn btn-warning btn-custom"
                                                    wire:click="evaluarSolicitud({{ $proyecto->id }})"
                                                    title="Ver motivos de la solicitud">
                                                    <i class="fas fa-file-alt"></i> Ver motivos
                                                </button>
                                            </td>
                                        @elseif ($proyecto->estado !== 2)
                                            <td>
                                                <button class="btn btn-success btn-sm btn-custom"
                                                    onclick="mostrarNotificacion()"
                                                    wire:click="evaluarSolicitud({{ $proyecto->id }})"
                                                    title="Ya has respondido a esta solicitud">
                                                    <i class="fas fa-check-circle"></i> Solicitud respondida
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($proyectos->hasPages())
                            <div class="px-6 py-3">
                                {{ $proyectos->links() }}
                            </div>
                        @endif
                    @else
                        <div class='px-6 py-2'>
                            <p>No hay resultados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrarNotificacion() {
            Swal.fire({
                icon: 'success',
                title: 'Solicitud respondida',
                text: 'Esta solicitud ya ha sido atendida.',
                showConfirmButton: true,
                confirmButtonText: 'Entendido'
            });
        }
    </script>

</div>
