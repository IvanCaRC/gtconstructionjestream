<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <h1 class="pl-4">Familias registradas</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="card">
            <div class="card-body">
                <div class="text-left mb-3">
                    <button class="btn btn-custom"
                        onclick="window.location.href='{{ route('compras.familias.createFamilias') }}'"
                        style="background-color: #4c72de; color: white;">Agregar familia</button>
                </div>
                <div class="table-responsive">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Input de búsqueda -->
                        <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm'
                            wire:keydown='search' placeholder="Buscar familia...">
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                @if ($familias->count() > 0)
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="categoria-header">
                                <div><strong>Nombre</strong></div>
                                <div><strong>Descripción</strong></div>
                                <div><strong>Acciones</strong></div>
                            </div>
                        </li>
                        @foreach ($familias as $familia)
                            @include('livewire.familia.categoria', ['familia' => $familia, 'nivel' => 1])
                        @endforeach
                    </ul>
                @else
                    <div class='px-6 py-2'>
                        <p>No hay resultados</p>
                    </div>
                @endif
                <!-- Enlaces de paginación -->
                <div class="px-6 py-3">
                    {{ $familias->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
