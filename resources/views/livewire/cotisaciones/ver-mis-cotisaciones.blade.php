<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3">
    <h2 class="ml-3">Mis Cotizaciones</h2>
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
                        <option value="0">Activa</option>
                        <option value="1">Enviada</option>
                        <option value="2">Cancelada</option>
                        <option value="3">Terminada</option>
                    </select>
                </div>
            </div>

            @if ($listasCotizar && $listasCotizar->count() > 0)

                <div wire:poll.3000ms>
                    <table class="table">
                        <thead>
                            <tr>
                                @if (Auth::user()->hasRole('Administrador'))
                                    <th>Usuario</th>
                                @endif
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
                                    @if (Auth::user()->hasRole('Administrador'))
                                        <td>
                                            {{ $lista->usuarioCompras->name ?? 'Sin asignar' }}
                                            {{ $lista->usuarioCompras->first_last_name ?? 'Sin asignar' }}
                                            {{ $lista->usuarioCompras->second_last_name ?? 'Sin asignar' }}
                                        </td>
                                    @endif

                                    <td>Cotisacion {{ $lista->nombre }}</td>
                                    <td>{{ $lista->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $lista->proyecto->preferencia == 1 ? 'Tiempo de entrega' : ($lista->proyecto->preferencia == 2 ? 'Precio' : 'Sin preferencia') }}
                                    </td>
                                    <td>

                                        <label>
                                            {!! $lista->estado == 0
                                                ? '<span class="badge badge-success">Activa</span>'
                                                : ($lista->estado == 1
                                                    ? '<span class="badge badge-primary">Enviada</span>'
                                                    : ($lista->estado == 2
                                                        ? '<span class="badge badge-danger">Cancelada</span>'
                                                        : ($lista->estado == 3
                                                            ? '<span class="badge badge-success">Venta terminada</span>'
                                                            : '<span class="badge badge-secondary">Estado desconocido</span>'))) !!}
                                        </label>

                                    </td>
                                    <td>
                                        <!-- Botón para ver detalles -->
                                        <button class="btn btn-info btn-sm mr-1"
                                            wire:click="verDetalles({{ $lista->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if ($lista->estado == 0)
                                            <button class="btn btn-primary btn-sm mr-1" title="Editar"
                                                wire:click="editarlista({{ $lista->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        @if ($lista->estado != 2)
                                            <button class="btn btn-danger btn-sm"
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
                    No tienes listas para cotizar.
                </div>
            @endif
            <!-- Enlace de paginación -->

        </div>
    </div>

</div>
