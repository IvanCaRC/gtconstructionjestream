<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <h1 class="pl-4">Familias registradas</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="card">
            <div class="card-body">
                <div class="text-left mb-3">
                    <button class="btn btn-custom" wire:click="$set('open', true)"
                        style="background-color: #4c72de; color: white;">Agregar categoría</button>
                </div>
                <div class="table-responsive">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Input de búsqueda -->
                        <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm' wire:keydown='search'
                         placeholder="Buscar familia...">
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

                <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                <script>
                    function toggleVisibility(id) {
                        $('#' + id).animate({
                            height: 'toggle'
                        }, 500);

                        var icon = $('#toggleButton' + id + ' i');
                        if (icon.hasClass('fa-chevron-down')) {
                            icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                        } else {
                            icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                        }

                        var folderIcon = $('#folderIcon' + id + ' i');
                        if (folderIcon.hasClass('fa-folder')) {
                            folderIcon.removeClass('fa-folder').addClass('fa-folder-open');
                        } else {
                            folderIcon.removeClass('fa-folder-open').addClass('fa-folder');
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</div>
