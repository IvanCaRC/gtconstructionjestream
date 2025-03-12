<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <h1 class="pl-4">Clientes registrados </h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="flex h-screen gap-4 p-4">
            <div class="flex-1 bg-white p-4 rounded-lg border border-black" style="flex: 0 0 85%;">
                <div class="card-body">
                    <div class="text-left mb-3">
                        <button class="btn btn-custom"
                            onclick="window.location.href='{{ route('ventas.clientes.recepcionLlamadas') }}'"
                            style="background-color: #4c72de; color: white;">Registrar nuevo cliente</button>
                    </div>
                    <div class="table-responsive">
                        <div class="d-flex justify-content-between mb-3">
                            <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm' wire:keydown='search'
                                placeholder="Buscar cliente...">
                        </div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    @if ($clientes->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th class="d-none d-md-table-cell">Correo</th>
                                    <th class="d-none d-md-table-cell">RFC</th>
                                    <th>Proyectos Totales</th>
                                    <th>Proyectos Activos</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->nombre }}</td>
                                        <td class="d-none d-md-table-cell">{{ $cliente->correo }}</td>
                                        <td class="d-none d-md-table-cell">{{ $cliente->rfc }}</td>
                                        <td>{{ $cliente->proyectos }}</td>
                                        <td>{{ $cliente->proyectos_activos }}</td>
                                        <td>
                                            <button class="btn btn-info btn-custom"
                                                wire:click="viewCliente({{ $cliente->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-custom"
                                                ><i
                                                    class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Paginación -->
                        {{ $clientes->links() }}
                    @else
                        <div class='px-6 py-2'>
                            <p>No hay resultados</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="bg-white rounded-lg border border-black p-4" style="flex: 0 0 15%;">
                <div class="card-body">
                    <h3>Filtros</h3>
                    <select class="form-control" wire:model="statusFiltroDeBusqueda" wire:change="filter">
                        <option value="2">Todos</option>
                        <option value="1">Proyectos activos</option>
                        <option value="0">Proyectos inactivos</option>
                    </select>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>