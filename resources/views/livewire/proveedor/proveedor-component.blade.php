<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <!-- Otros enlaces y estilos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <h1 class="pl-4">Proveedores registrados</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="flex h-screen gap-4 p-4">
            <div class="flex-1 bg-white p-4 rounded-lg border border-black" style="flex: 0 0 85%;">
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
                                    <th>Estado</th>
                                    <th>Nombre</th>
                                    {{-- <th class="d-none d-md-table-cell" wire:click="" style="cursor: pointer;">
                    Descripcion
                </th> --}}
                                    <th class="d-none d-md-table-cell" wire:click="" style="cursor: pointer;">
                                        Correo
                                    </th>
                                    <th class="d-none d-md-table-cell" wire:click="" style="cursor: pointer;">
                                        RFC
                                    </th>
                                    <th>Direccion(es)</th>
                                    <th>Familias</th>
                                    <th>Telefonos</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proveedores as $proveedor)
                                    <tr>
                                        <td class="align-middle d-none d-md-table-cell">
                                            @if ($proveedor->estado)
                                                <span class="badge badge-success">Actualizado</span>
                                            @else
                                                <span class="badge badge-danger">Desactualizado</span>
                                                <label for="">Última fecha de actualización:</label>
                                                {{$proveedor->updated_at->format('d/m/Y') }}
                                            @endif
                                            
                                        </td>
                                        <td class="align-middle">{{ $proveedor->nombre }}</td>
                                        {{-- <td class="align-middle">{{ $proveedor->descripcion }}</td> --}}
                                        <td class="align-middle d-none d-md-table-cell">{{ $proveedor->correo }}</td>
                                        <td class="align-middle d-none d-md-table-cell">{{ $proveedor->rfc }}</td>
                                        <td class="align-middle">
                                            @if ($proveedor->direcciones && $proveedor->direcciones->count() > 0)
                                                @foreach ($proveedor->direcciones as $direccion)
                                                    {{ $direccion->estado }}, {{ $direccion->ciudad }},
                                                    {{ $direccion->calle }}, {{ $direccion->numero }}<br>
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @foreach ($proveedor->familias as $familia)
                                                {{ $familia->nombre }}
                                            @endforeach
                                        </td>
                                        <td class="align-middle">
                                            @if ($proveedor->telefonos && $proveedor->telefonos->count() > 0)
                                                @foreach ($proveedor->telefonos as $telefono)
                                                    {{ $telefono->numero }}<br>
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-custom"
                                                wire:click="viewProveedor({{ $proveedor->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-custom"
                                                wire:click="editProveedor({{ $proveedor->id }})"><i
                                                    class="fas fa-edit"></i></button>
                                        </td>
                                        <td><button class="btn btn-danger btn-custom"
                                                onclick="confirmDeletion({{ $proveedor->id }}, '{{ $proveedor->nombre }}')">
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
                                                            ejecutarEliminacionProveedor(proveedorId, proveedorNombre);
                                                        } else {
                                                            Swal.fire('Cancelado', 'La eliminación ha sido cancelada.', 'info');
                                                        }
                                                    });
                                                }

                                                function ejecutarEliminacionProveedor(proveedorId, proveedorNombre) {
                                                    @this.call('verificarAsignacionProvedor', proveedorId).then((asignada) => {
                                                        if (asignada) {
                                                            Swal.fire(
                                                                'No se puede eliminar',
                                                                'Este proveedor está asignada a un item, no se puede eliminar.',
                                                                'error'
                                                            );
                                                        } else {
                                                            @this.call('eliminar', proveedorId).then(() => {
                                                                Swal.fire(
                                                                    'Eliminado!',
                                                                    `${proveedorNombre} ha sido eliminado.`,
                                                                    'success'
                                                                );
                                                            });
                                                        }
                                                    });
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
            <div class="bg-white rounded-lg border border-black p-4" style="flex: 0 0 15%;">
                <div class="card-body">
                    <h3>Filtros</h3>
                    <select class="form-control" wire:model="statusFiltroDeBusqueda" wire:change="filter">
                        <option value="2">Todos</option>
                        <option value="1">Actualizado</option>
                        <option value="0">Desactualizado</option>
                    </select>
                    <br>
                    <strong>Categorías</strong>
                    <ul>
                        @foreach($familias as $familia)
                            @include('livewire.familia.lista-categorias', ['familia' => $familia, 'nivel' => 0])
                        @endforeach
                    </ul>
                </div>
            </div>
            
        </div>

    </div>
</div>
