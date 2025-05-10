<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3">
    <h2 class="ml-3">Listas por cotizar</h2>
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
                        <option value="0">Sin Preferencia</option>
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
                                <th>Proyecto</th>
                                <th>Nombre</th>
                                <th>Fecha de Creación</th>
                                <th>Preferencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listasCotizar as $lista)
                                <tr>
                                    <td>{{ $lista->proyecto->nombre }}</td>
                                    <td>Lista {{ $lista->nombre }}</td>
                                    <td>{{ $lista->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $lista->proyecto->preferencia == 1 ? 'Tiempo de entrega' : ($lista->proyecto->preferencia == 2 ? 'Precio' : 'Sin preferencia') }}
                                    </td>
                                    <td>
                                        <!-- Botón de generación de PDF -->
                                        <button class="btn btn-info text-white"
                                            style="width: 160px; height: 35px; white-space: nowrap;"
                                            onclick="window.open('{{ route('proyecto.pdf-lista-compras', ['id' => $lista->id]) }}', '_blank')"
                                            title="Generar y ver el documento de la lista">
                                            <i class="fas fa-file-pdf me-1"></i> Archivo de lista
                                        </button>

                                        <!-- Botón de selección -->
                                        <button class="btn btn-primary btn-custom" style="width: 120px; height: 35px;"
                                            wire:click="seleccionar({{ $lista->id }})">
                                            Seleccionar
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
                    Actualmente no hay listas por cotizar.
                </div>
            @endif
            <!-- Enlace de paginación -->

        </div>
    </div>
    @include('livewire.cotisaciones.modalSeleccionUsuario')

</div>
