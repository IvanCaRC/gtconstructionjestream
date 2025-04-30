<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3">
    <h2 class="ml-3">Recepcion de Cotizaciones</h2>
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
            @if ($listasCotizar && $listasCotizar->count() > 0)

                <div wire:poll.3000ms>
                    <table class="table">
                        <thead>
                            <tr>
                                @if (Auth::user()->hasRole('Administrador'))
                                    <th>Usuario</th>
                                @endif
                                <th>Ciente</th>
                                <th>Proyecto</th>
                                <th>Lista</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listasCotizar as $lista)
                                <tr>
                                    @if (Auth::user()->hasRole('Administrador'))
                                        <td>
                                            {{ $lista->proyecto->cliente->user->name ?? 'Sin asignar' }}
                                            {{ $lista->proyecto->cliente->user->first_last_name ?? 'Sin asignar' }}
                                            {{ $lista->proyecto->cliente->user->second_last_name ?? 'Sin asignar' }}
                                        </td>
                                    @endif

                                    <td>{{ $lista->proyecto->cliente->nombre }}</td>
                                    <td>{{ $lista->proyecto->nombre }}</td>
                                    <td>
                                        Lista {{ $lista->listaCotizar->nombre }}
                                    </td>
                                    <td>
                                        <!-- Botón para Ver Proyecto -->
                                        <button class="btn btn-primary btn-sm"
                                             wire:click="viewProyecto({{ $lista->proyecto->id }})"
                                            title="Ver detalles del proyecto">
                                            <i class="fas fa-eye me-1"></i> Proyecto
                                        </button>
                                        <!-- Botón de Cancelar (existente) -->
                                        <button class="btn btn-danger btn-sm" wire:click="cancelar({{ $lista->id }})"
                                            title="Cancelar cotización">
                                            <i class="fas fa-times me-1"></i> Cancelar
                                        </button>

                                        <button class="btn btn-primary btn-sm" wire:click="cancelar({{ $lista->id }})"
                                            title="Cancelar cotización">
                                            Aceptar
                                        </button>
                                        <button class="btn btn-info btn-sm text-white"
                                            wire:click="generarPDF({{ $lista->id }})"
                                            title="Generar PDF de la cotización">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </button>
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
