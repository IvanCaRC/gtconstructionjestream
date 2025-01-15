<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <h1 class="pl-4">Materiales en inventario</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="card">
            <div class="card-body">
                <div class="text-left mb-3">
                    <button class="btn btn-custom" {{-- Cambiar cuando tenga el createItems --}}
                        onclick="window.location.href='{{ route('compras.items.createItems') }}'"
                        style="background-color: #4c72de; color: white;">Registrar nuevo Material</button>
                </div>

                <div class="table-responsive">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Input de búsqueda -->
                        <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm'
                            wire:keydown='search' placeholder="Buscar item...">
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                @if ($items1->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Actualizacion</th>
                                <th>Nombre</th>
                                <th>Categoria</th>
                                <th>Precio de proveedor</th>
                                <th>Precio minorista</th>
                                <th>Precio mayorista</th>
                                <th>Unidad de medida</th>
                                <th>Piezas para mayoreo</th>
                                <th>Proveedores</th>
                                <th>Tiempo de entrega</th>
                                <th>Ultima modificación</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items1 as $item1)
                                <tr>
                                    <td></td>
                                    <td class="align-middle d-none d-md-table-cell">{{ $item1->nombre }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="align-middle d-none d-md-table-cell">{{ $item1->updated_at }}</td>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class='px-6 py-2'>
                        <p>No hay resultados</p>
                    </div>
                @endif

                <!-- Enlaces de paginación -->
                {{-- <div class="px-6 py-3">
                    {{ $items->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
