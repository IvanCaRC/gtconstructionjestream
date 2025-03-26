<div>
    <div class="row bg-white py-4  shadow">

        @if ($listadeUsuarioActiva == null)
            <div class="col-md-9">
                <h4 class="px-3">
                    No hay una lista activa. Activa o crea una para realizar la lista.
                </h4>
            </div>
        @else
            <div class="col-md-9">
                <h4 class="px-3">
                    Lista activa de cliente "<span class="fw-bold text-primary ">{{ $nombreCliente }}</span>",
                    del proyecto "<span class="fw-bold text-primary ">{{ $nombreProyecto }}</span>",
                    y lista "<span class="fw-bold text-primary ">{{ $listadeUsuarioActiva }}</span>".
                </h4>
            </div>
        @endif
        <div class="col-md-1">
            <a href="#" class=" text-danger d-block">Cancelar</a>
        </div>
        <div class="col-md-1">
            <a href="#" class="d-block">Desactivar</a>
        </div>
        <div class="col-md-1">
            <button class="btn btn-light border-0 shadow-sm " style="width: 50px; height: 50px;"  wire:click="verLista({{$idLista}})">
                <i class="fas fa-shopping-cart text-primary" style="font-size: 24px;"></i>
            </button>
        </div>
    </div>


    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="flex h-screen gap-4 p-4">
            <!-- Primera sección (85%) -->
            <div class="flex-1 bg-white p-4 rounded-lg border border-black" style="flex: 0 0 85%;">
                <div class="card-body">

                    <div class="table-responsive">
                        <div class="d-flex justify-content-between mb-3">
                            <!-- Input de búsqueda -->
                            <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm'
                                wire:keydown='search' placeholder="Buscar ítem...">
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
                    <div>
                        @if ($itemEspecificos->count() > 0)
                            @if (!$tipoDeVista)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Estado</th>
                                            <th>Nombre</th>
                                            <th>Familia</th>
                                            <th>Marca</th>
                                            <th>Unidad de venta</th>
                                            <th>MOC (Minimo de Venta)</th>
                                            <th>Unidades mayoristas</th>
                                            <th>Última modificación</th>
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

                                                <td class="align-middle d-none d-md-table-cell">
                                                    {{ $itemEspecifico->item->nombre }}</td>

                                                <td class="align-middle d-none d-md-table-cell">
                                                    @foreach ($itemEspecifico->familias as $familia)
                                                        {{ $familia->nombre }}
                                                    @endforeach
                                                </td>

                                                <td class="align-middle d-none d-md-table-cell">
                                                    {{ $itemEspecifico->marca ?? 'N/A' }}
                                                </td>

                                                <td class="align-middle d-none d-md-table-cell">
                                                    {{ $itemEspecifico->unidad ?? 'N/A' }}
                                                </td>

                                                <td class="align-middle d-none d-md-table-cell">
                                                    {{ $itemEspecifico->MOC ?? 'N/A' }}
                                                </td>

                                                <td class="align-middle d-none d-md-table-cell">
                                                    {{ $itemEspecifico->cantidad_piezas_mayoreo ?? 'N/A' }}
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
                                                    <button class="btn btn-success btn-custom"
                                                        wire:click="addToCart({{ $itemEspecifico->id }})"
                                                        title="Añadir a tu lista">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="row">
                                    @foreach ($itemEspecificos as $itemEspecifico)
                                        <div class="col-md-3 mb-4">
                                            <div class="card shadow-sm">
                                                <div class="card-body text-center d-flex justify-content-center align-items-center"
                                                    style="cursor: pointer;">
                                                    <!-- Imagen del Item -->
                                                    @if (empty($itemEspecifico->image))
                                                        <!-- Mostrar ícono o mensaje alternativo -->
                                                        <div class="no-image-icon"
                                                            style="width: 200px; height: 200px; display: flex; justify-content: center; align-items: center; border: 1px solid #ddd; background-color: #f8f8f8;">
                                                            📷 No hay imagen subida
                                                        </div>
                                                    @else
                                                        <!-- Mostrar imagen -->
                                                        <img src="{{ asset('storage/' . explode(',', $itemEspecifico->image)[0]) }}"
                                                            class="card-img-top"
                                                            alt="{{ $itemEspecifico->item->nombre }}"
                                                            style="width: 200px; height: 200px; object-fit: cover;"
                                                            wire:click="viewItem({{ $itemEspecifico->id }})">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <!-- Estado -->
                                                    <span
                                                        class="badge {{ $itemEspecifico->estado ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $itemEspecifico->estado ? 'Actualizado' : 'Desactualizado' }}
                                                    </span>
                                                    <br>
                                                    <!-- Nombre con enlace a la vista específica -->
                                                    <div>
                                                        <label style="width: 200px; height: 65px; object-fit: cover;">
                                                            <a class="text-primary"
                                                                wire:click="viewItem({{ $itemEspecifico->id }})"
                                                                style="cursor: pointer;">
                                                                {{ $itemEspecifico->item->nombre }}
                                                            </a>
                                                        </label>
                                                    </div>

                                                    <div>
                                                        <label>
                                                            <strong>Marca:</strong>
                                                            {{ $itemEspecifico->marca }}
                                                        </label>
                                                    </div>

                                                    <!-- Especificaciones del material -->
                                                    <p class="card-text">
                                                        <strong>Unidad de venta:</strong>

                                                        {{ $itemEspecifico->unidad ?? 'N/A' }} <br>
                                                        <strong>MOC:</strong>

                                                        {{ $itemEspecifico->MOC ?? 'N/A' }} <br>

                                                        <strong>Cant. Pz Mayoreo:</strong>
                                                        {{ $itemEspecifico->cantidad_piezas_mayoreo ?? 'N/A' }} <br>

                                                        <strong>Precio Minorista:</strong>
                                                        ${{ $itemEspecifico->precio_venta_minorista ?? 'N/A' }} <br>

                                                        <strong>Precio Mayorista:</strong>
                                                        ${{ $itemEspecifico->precio_venta_mayorista ?? 'N/A' }} <br>
                                                    </p>
                                                    <!-- Fecha de Actualización -->
                                                    <p class="card-text text-muted">
                                                        Última actualización:
                                                        {{ $itemEspecifico->item->updated_at->format('d/m/Y') }}
                                                    </p>

                                                    <td>
                                                        <button class="btn btn-success btn-custom"
                                                        wire:click="agregarItemLista({{ $itemEspecifico->id }})"
                                                            title="Agrega este item a tu lista">
                                                            <i class="fas fa-shopping-cart"> Añadir a la lista</i>
                                                        </button>
                                                    </td>

                                                    <td>
                                                        <button class="btn btn-info btn-custom"
                                                            wire:click="viewItem({{ $itemEspecifico->id }})"
                                                            title="Observa a detalle">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
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
            <!-- Segunda sección (15%) -->
            <div class="bg-white rounded-lg border border-black p-4" style="flex: 0 0 15%;">
                <div class="card-body">
                    <h3>Filtros</h3>
                    <select class="form-control" wire:model="statusFiltroDeBusqueda" wire:change="filter">
                        <option value="2">Todos</option>
                        <option value="1">Actualizado</option>
                        <option value="0">Desactualizado</option>
                    </select>
                    <br>
                    <strong>Categorías/Familias</strong>
                    <ul>
                        @foreach ($familias as $familia)
                            @include('livewire.familia.lista-categorias', [
                                'familia' => $familia,
                                'nivel' => 0,
                            ])
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
