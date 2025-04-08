<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3">
    <h2 class="ml-3">Mis cotisaciones </h2>
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
                        <option value="0">Preferencia</option>
                        <option value="1">Tiempo de entrega</option>
                        <option value="2">Precio</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2" wire:model="estado" wire:change="search">
                        <option value="">Estado</option>
                        <option value="0">Inactiva</option>
                        <option value="1">Activa</option>
                        <option value="2">Enviada</option>
                        <option value="3">Cancelada</option>
                        <option value="4">Terminada</option>
                    </select>
                </div>
            </div>

            @if ($listasCotizar && $listasCotizar->count() > 0)

                <div wire:poll.3000ms>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha de Creación</th>
                                <th>Preferencia</th>
                                <th>Estado</th>
                                <th>accciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listasCotizar as $lista)
                                <tr>
                                    <td>Cotisacion {{ $lista->nombre }}</td>
                                    <td>{{ $lista->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $lista->proyecto->preferencia == 1 ? 'Tiempo de entrega' : ($lista->proyecto->preferencia == 2 ? 'Precio' : 'Sin preferencia') }}
                                    </td>
                                    <td>

                                        <label>
                                            {!! $lista->estado == 0
                                                ? '<span class="badge badge-secondary">Inactiva</span>'
                                                : ($lista->estado == 1
                                                    ? '<span class="badge badge-success">Activa</span>'
                                                    : ($lista->estado == 2
                                                        ? '<span class="badge badge-primary">Enviada</span>'
                                                        : ($lista->estado == 3
                                                            ? '<span class="badge badge-danger">Cancelada</span>'
                                                            : ($lista->estado == 4
                                                                ? '<span class="badge badge-success">Venta terminada</span>'
                                                                : '<span class="badge badge-secondary">Estado desconocido</span>')))) !!}
                                        </label>

                                    </td>
                                    <td>
                                        <!-- Botón para ver detalles -->
                                        <button class="btn btn-primary btn-custom"
                                            wire:click="verDetalles({{ $lista->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Botones según el estado de la cotización -->
                                        @if ($lista->estado == 0)
                                            <!-- Estado Inactiva: Mostrar botón Activar y Cancelar -->
                                            <button class="btn btn-success btn-custom"
                                                wire:click="activar({{ $lista->id }})">Activar</button>
                                            <button class="btn btn-danger btn-custom"
                                                wire:click="cancelar({{ $lista->id }})">Cancelar</button>
                                        @elseif ($lista->estado == 1)
                                            <!-- Estado Activa: Mostrar botón Desactivar y Cancelar -->
                                            <button class="btn btn-secondary btn-custom"
                                                wire:click="desactivar({{ $lista->id }})">Desactivar</button>
                                            <button class="btn btn-danger btn-custom"
                                                wire:click="cancelar({{ $lista->id }})">Cancelar</button>
                                        @endif
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $listasCotizar->links() }}
                </div>
            @else
                <div>
                    No hay listas para poder cotisar.
                </div>
            @endif
            <!-- Enlace de paginación -->

        </div>
    </div>

</div>
