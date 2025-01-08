<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <h1 class="pl-4">Proveedores registrados</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="card">
            <div class="card-body">
                <div class="text-left mb-3">
                    <button class="btn btn-custom" {{-- Cambiar cuando tenga el createProveedores --}}
                        onclick="window.location.href='{{ route('compras.familias.createFamilias') }}'"
                        style="background-color: #4c72de; color: white;">Registrar nuevo Proveedor</button>
                </div>

                <div class="table-responsive">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Input de búsqueda -->
                        <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm'
                            placeholder="Buscar proveedor...">
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                @if ($proveedores->count() > 0)
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="categoria-header">
                                <div><strong>Nombre</strong></div>
                                <div><strong>RFC</strong></div>
                                <div><strong>Direccion</strong></div>
                                <div><strong>Familia de materiales</strong></div>
                                <div><strong>Correo</strong></div>
                                <div><strong>Telefono</strong></div>
                                <div><strong>Datos bancarios</strong></div>
                                <div><strong>Total de compra</strong></div>
                                <div><strong>Credito de compra</strong></div>
                            </div>

                            {{-- Agregar atributos para listar en la vista de proveedores --}}
                            @foreach ($proveedores as $proveedor)
                                <tr>
                                    <td class="align-middle d-none d-md-table-cell">{{ $proveedor->nombre }}</td>
                                    <td class="align-middle d-none d-md-table-cell">{{ $proveedor->rfc }}</td>
                            @endforeach
                        </li>
                    </ul>
                @else
                    <div class='px-6 py-2'>
                        <p>No hay resultados</p>
                    </div>
                @endif

                <!-- Enlaces de paginación -->
                <div class="px-6 py-3">
                    {{ $proveedores->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
