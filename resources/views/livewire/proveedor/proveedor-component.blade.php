<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <h1 class="pl-4">Proveedores registrados</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="card">
            <div class="card-body">
                <div class="text-left mb-3">
                    <button class="btn btn-custom"
                        onclick="window.location.href='{{ route('compras.proveedores.createProveedores') }}'"
                        style="background-color: #4c72de; color: white;">Registrar nuevo Proveedor</button>
                </div>

                <div class="table-responsive">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Input de búsqueda -->
                        <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm'
                            wire:keydown='search' placeholder="Buscar proveedor...">
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                @if ($proveedores->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th class="d-none d-md-table-cell" wire:click="" style="cursor: pointer;">
                                    Descripcion
                                </th>
                                <th class="d-none d-md-table-cell" wire:click="" style="cursor: pointer;">
                                    Correo
                                </th>
                                <th class="d-none d-md-table-cell" wire:click="" style="cursor: pointer;">
                                    RFC
                                </th>
                                <th>Estado</th>
                                <th>Familias</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proveedores as $proveedor)
                                <tr>
                                    <td class="align-middle">{{ $proveedor->nombre }}</td>
                                    <td class="align-middle">{{ $proveedor->descripcion }}</td>
                                    <td class="align-middle d-none d-md-table-cell">{{ $proveedor->correo }}</td>
                                    <td class="align-middle d-none d-md-table-cell">{{ $proveedor->rfc }}</td>
                                    <td class="align-middle d-none d-md-table-cell">
                                        @if ($proveedor->estado)
                                            <span class="badge badge-success">Actualizado</span>
                                        @else
                                            <span class="badge badge-danger">Desactualizado</span>
                                        @endif
                                    </td>
                                    <td>----</td>
                                    <td>
                                        <button class="btn btn-info btn-custom" wire:click="viewProveedor({{ $proveedor->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-custom"  onclick="window.location.href='{{ route('compras.proveedores.createProveedores') }}'"><i
                                                class="fas fa-edit"></i></button>
                                    </td>
                                    <td><button class="btn btn-danger btn-custom" onclick="confirmDeletion({{ $proveedor->id }}, '{{ $proveedor->nombre }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <script>
                                            function confirmDeletion(proveedorId, proveedorNombre) {
                                                Swal.fire({
                                                    title: `¿Estás seguro de que deseas eliminar a ${proveedorNombre}?`,
                                                    text: "¡No podrás revertir esto!",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#3085d6',
                                                    confirmButtonText: 'Sí, eliminar',
                                                    cancelButtonText: 'Cancelar'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        @this.call('eliminar', proveedorId);
                                                        Swal.fire(
                                                            'Eliminado!',
                                                            `${proveedorNombre} ha sido eliminado.`,
                                                            'success'
                                                        )
                                                    }
                                                })
                                            }
                                        </script>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
