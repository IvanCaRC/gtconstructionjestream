<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <h1 class="pl-4">Materiales en el inventario</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="card">
            <div class="card-body">
                <div class="text-left mb-3">
                    <button class="btn btn-custom" {{-- Cambiar cuando tenga el createItems --}}
                        onclick="window.location.href='{{ route('compras.familias.createFamilias') }}'"
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
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="categoria-header">
                                <div><strong>Nombre</strong></div>
                                <div><strong>Categoria</strong></div>
                                <div><strong>Precio de proveedor</strong></div>
                                <div><strong>Precio minorista</strong></div>
                                <div><strong>Precio mayorista</strong></div>
                                <div><strong>Unidad de medida</strong></div>
                                <div><strong>Piezas para mayoreo</strong></div>
                                <div><strong>Proveedores</strong></div>
                                <div><strong>Descripcion</strong></div>
                                <div><strong>Especificaciones tecnicas</strong></div>
                                <div><strong>Recursos adicionales</strong></div>
                                <div><strong>Tiempo de entrega</strong></div>
                                <div><strong>Ultima modificacion</strong></div>

                            </div>

                            {{-- Agregar atributos para listar en la vista de proveedores --}}
                            @foreach ($items1 as $item1)
                                <tr>
                                    <td class="align-middle d-none d-md-table-cell">{{ $item1->nombre }}</td>
                                    {{-- <td class="align-middle d-none d-md-table-cell">{{ $item1->updated_at }}</td> --}}
                            @endforeach
                        </li>
                    </ul>
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
