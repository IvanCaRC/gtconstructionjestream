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
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Input de búsqueda -->
                        <button class="p-2 border rounded focus:outline-none" wire:click="$toggle('tipoDeVista')">
                            @if (!$tipoDeVista)
                                <!-- Icono de cuadrícula -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="blue"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="8" height="8" fill="blue"></rect>
                                    <rect x="13" y="3" width="8" height="8" fill="lightgray"></rect>
                                    <rect x="3" y="13" width="8" height="8" fill="lightgray"></rect>
                                    <rect x="13" y="13" width="8" height="8" fill="lightgray"></rect>
                                </svg>
                            @else
                                <!-- Icono de lista --> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="blue"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="8" height="4" fill="blue"></rect>
                                    <rect x="3" y="9" width="8" height="4" fill="lightgray"></rect>
                                    <rect x="3" y="15" width="8" height="4" fill="lightgray"></rect>
                                    <rect x="13" y="3" width="8" height="4" fill="lightgray"></rect>
                                </svg>
                            @endif
                        </button>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                @if ($itemEspecificos->count() > 0)
                    @if (!$tipoDeVista)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Estado</th>

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
                                @foreach ($itemEspecificos as $itemEspecifico)
                                <tr>
                                    <td class="align-middle d-none d-md-table-cell">
                                        @if ($itemEspecifico->estado)
                                            <span class="badge badge-success">Actualizado</span>
                                        @else
                                            <span class="badge badge-danger">Desactualizado</span>
                                        @endif
                                    </td>
                            
                                    <td class="align-middle d-none d-md-table-cell">{{ $itemEspecifico->item->nombre }}</td>
                            
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
                                        {{ $itemEspecifico->precio_venta_minorista ?? 'N/A' }}
                                    </td>
                            
                                    <td class="align-middle d-none d-md-table-cell">
                                        {{ $itemEspecifico->precio_venta_mayorista ?? 'N/A' }}
                                    </td>
                            
                                    <td class="align-middle d-none d-md-table-cell">
                                        {{ $itemEspecifico->unidad ?? 'N/A' }}
                                    </td>
                            
                                    <td class="align-middle d-none d-md-table-cell">
                                        {{ $itemEspecifico->item->updated_at }}
                                    </td>
                            
                                    <td>
                                        <button class="btn btn-info btn-custom"
                                            wire:click="viewItem({{ $itemEspecifico->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                            
                                    <td>
                                        <button class="btn btn-primary btn-custom"
                                            wire:click="editItem({{ $itemEspecifico->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                            
                            

                                    <td>
                                        <button class="btn btn-danger btn-custom"
                                            onclick="confirmDeletion({{ $itemEspecifico->id }}, '{{ $itemEspecifico->item->nombre }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        
                                    </td>
                                </tr>
                            @endforeach
                            

                            
                            </tbody>
                        </table>
                    @else
                    <div class="row">
                        @foreach ($itemEspecificos as $itemEspecifico)
                            <div class="col-md-2 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-body text-center">
                                        <!-- Imagen del Item -->
                                        <img src="{{ $itemEspecifico->image ? asset('storage/' . explode(',', $itemEspecifico->image)[0]) : asset('storage/ruta_por_defecto.jpg') }}"
                                            class="card-img-top" alt="{{ $itemEspecifico->item->nombre }}"
                                            style="width: 200px; height: 200px; object-fit: cover;">
                                    </div>
                                </div>
                    
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <!-- Estado -->
                                        <span class="badge {{ $itemEspecifico->estado ? 'badge-success' : 'badge-danger' }}">
                                            {{ $itemEspecifico->estado ? 'Actualizado' : 'Desactualizado' }}
                                        </span>
                    
                                        <!-- Nombre con enlace a la vista específica -->
                                        <h5 class="card-title">
                                            <a class="text-primary">
                                                {{ \Illuminate\Support\Str::limit($itemEspecifico->item->nombre, 15, '...') }}
                                            </a>
                                        </h5>
                    
                                        <!-- Precios -->
                                        <p class="card-text">
                                            <strong>Precio Minorista:</strong> {{ $itemEspecifico->precio_venta_minorista ?? 'N/A' }} <br>
                                            <strong>Precio Mayorista:</strong> {{ $itemEspecifico->precio_venta_mayorista ?? 'N/A' }}
                                        </p>
                    
                                        <!-- Stock -->
                                        <p class="card-text">
                                            <strong>Stock:</strong> {{ $itemEspecifico->stock ?? 'N/A' }}
                                        </p>
                    
                                        <!-- Fecha de Actualización -->
                                        <p class="card-text text-muted">
                                            Última actualización: {{ $itemEspecifico->item->updated_at->format('d/m/Y') }}
                                        </p>
                    
                                        <!-- Botón de Ver -->
                                        <button class="btn btn-info btn-sm"
                                            wire:click="viewItem({{ $itemEspecifico->id }})">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Paginación -->



                    @endif
                @else
                    <div class='px-6 py-2'>
                        <p>No hay resultados</p>
                    </div>
                @endif
                <!-- Enlaces de paginación -->
                @if ($itemEspecificos->hasPages())
                    <div class="px-6 py-3">
                        {{ $itemEspecificos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
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
</div>
