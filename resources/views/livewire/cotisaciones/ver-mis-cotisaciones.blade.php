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
                        <option value="2">Aceptada pendiente de pago</option>
                        <option value="3">Pagado</option>
                        <option value="4">Comprando</option>
                        <option value="5">En proceso de entrega</option>
                        <option value="6">Terminado</option>
                        <option value="7">Cancelado</option>
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

                                    <td>Cotizacion {{ $lista->nombre }}</td>
                                    <td>{{ $lista->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $lista->proyecto->preferencia == 1 ? 'Tiempo de entrega' : ($lista->proyecto->preferencia == 2 ? 'Precio' : 'Sin preferencia') }}
                                    </td>
                                    <td>

                                        <label>
                                            {!! $lista->estado == 0
                                                ? '<span class="badge badge-primary">Activa</span>'
                                                : ($lista->estado == 1
                                                    ? '<span class="badge badge-primary">Enviada</span>'
                                                    : ($lista->estado == 2
                                                        ? '<span class="badge badge-warning">Aceptada pendiente de pago</span>'
                                                        : ($lista->estado == 3
                                                            ? '<span class="badge badge-primary">Pagado</span>'
                                                            : ($lista->estado == 4
                                                                ? '<span class="badge badge-warning">Comprando</span>'
                                                                : ($lista->estado == 5
                                                                    ? '<span class="badge badge-warning">En proceso de entrega</span>'
                                                                    : ($lista->estado == 6
                                                                        ? '<span class="badge badge-success">Terminado</span>'
                                                                        : ($lista->estado == 7
                                                                        ? '<span class="badge badge-primary">Cancelado</span>'
                                                                        : '<span class="badge badge-secondary">Estado desconocido</span>'))))))) !!}
                                        </label>

                                    </td>
                                    <td>
                                        <!-- Botón para ver detalles -->

                                        @if ($lista->estado == 0)
                                            @if (Auth::user()->cotizaciones != $lista->id)
                                                <button
                                                    class="btn btn-primary btn-sm mr-1 font-weight-bold border border-primary"
                                                    wire:click="verDetalles({{ $lista->id }})">
                                                    <i class="fas fa-list"></i> Seleccionar lista
                                                </button>
                                            @else
                                                <button
                                                    class="btn btn-success btn-sm mr-1 font-weight-bold border border-dark"
                                                    wire:click="verDetalles({{ $lista->id }})">
                                                    <i class="fas fa-check-circle"></i> Lista activa
                                                </button>
                                            @endif
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
