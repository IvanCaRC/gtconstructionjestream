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
                @if ($items->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Categoria</th>
                                <th>Precio de proveedor</th>
                                <th>Precio minorista</th>
                                <th>Precio mayorista</th>
                                <th>Unidad de medida</th>
                                <th>Ultima modificación</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                @foreach ($item->itemEspecificos as $itemEspecifico)
                                    <tr>
                                        <td class="align-middle d-none d-md-table-cell">
                                            @if ($itemEspecifico->estado)
                                                <span class="badge badge-success">Actualizado</span>
                                            @else
                                                <span class="badge badge-danger">Desactualizado</span>
                                            @endif
                                        </td>
                                        <td>---</td>
                                        <td class="align-middle d-none d-md-table-cell">{{ $item->nombre }}</td>
                                        <td class="align-middle d-none d-md-table-cell">
                                            @foreach ($itemEspecifico->familias as $familia)
                                                {{ $familia->nombre }}
                                            @endforeach
                                        </td>
                                        <td class="align-middle d-none d-md-table-cell">
                                            @foreach ($itemEspecifico->proveedores as $proveedor)
                                                {{ $proveedor->pivot->precio_compra }}
                                            @endforeach
                                        </td>
                                        <td class="align-middle d-none d-md-table-cell">
                                            {{ $itemEspecifico->precio_venta_minorista ?? 'N/A' }}</td>
                                        <td class="align-middle d-none d-md-table-cell">
                                            {{ $itemEspecifico->precio_venta_mayorista ?? 'N/A' }}</td>
                                        <td class="align-middle d-none d-md-table-cell">
                                            {{ $itemEspecifico->unidad ?? 'N/A' }}</td>
                                        <td class="align-middle d-none d-md-table-cell">{{ $item->updated_at }}</td>
                                        <td>
                                            <button class="btn btn-info btn-custom"
                                                wire:click="viewProveedor({{ $itemEspecifico->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-custom"
                                            wire:click="editItem({{ $itemEspecifico->id }})"><i
                                                class="fas fa-edit"></i></button>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-custom"
                                                onclick="confirmDeletion({{ $itemEspecifico->id }}, '{{ $item->nombre  }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <script>
                                                function confirmDeletion(itemEspecificoId, itemEspecificoNombre) {
                                                    Swal.fire({
                                                        title: `¿Estás seguro de que deseas eliminar  ${itemEspecificoNombre}?`,
                                                        text: "¡No podrás revertir esto!",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#d33',
                                                        cancelButtonColor: '#3085d6',
                                                        confirmButtonText: 'Sí, eliminar',
                                                        cancelButtonText: 'Cancelar'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            @this.call('eliminar', itemEspecificoId);
                                                            Swal.fire(
                                                                'Eliminado!',
                                                                `${itemEspecificoNombre} ha sido eliminado.`,
                                                                'success'
                                                            )
                                                        }
                                                    })
                                                }
                                            </script>
                                        </td>
                                    </tr>
                                @endforeach
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
