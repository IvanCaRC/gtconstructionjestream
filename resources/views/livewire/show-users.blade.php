<div>
    <h1 class="pl-4">Usuarios</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-12">
        <div class="card">
            <div class="card-body">
                @livewire('create-user')
                <div class="table-responsive">                    
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Input de búsqueda -->
                        <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm'
                            wire:keydown='search' placeholder="Buscar usuarios...">
                    </div>

                    @if ($users->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th class="d-none d-md-table-cell" wire:click="order('first_last_name')"
                                        style="cursor: pointer;">
                                        Nombre
                                        @if ($sort == 'first_last_name')
                                            @if ($direction == 'asc')
                                                <i class="fas fa-sort-up"></i>
                                            @else
                                                <i class="fas fa-sort-down"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </th>
                                    <th class="d-none d-md-table-cell" wire:click="order('email')"
                                        style="cursor: pointer;">
                                        Correo
                                        @if ($sort == 'email')
                                            @if ($direction == 'asc')
                                                <i class="fas fa-sort-up"></i>
                                            @else
                                                <i class="fas fa-sort-down"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </th>
                                    <th class="d-none d-md-table-cell" wire:click="order('number')"
                                        style="cursor: pointer;">
                                        Teléfono
                                        @if ($sort == 'number')
                                            @if ($direction == 'asc')
                                                <i class="fas fa-sort-up"></i>
                                            @else
                                                <i class="fas fa-sort-down"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </th>
                                    <th>Estado</th>
                                    <th>Departamento</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td><img src="https://via.placeholder.com/50" class="img-redonda"
                                                alt="Imagen de ejemplo"></td>
                                        <td class="align-middle">{{ $user->first_last_name }}
                                            {{ $user->second_last_name }} {{ $user->name }}</td>
                                        <td class="align-middle d-none d-md-table-cell">{{ $user->email }}</td>
                                        <td class="align-middle d-none d-md-table-cell">{{ $user->number }}</td>
                                        <td class="align-middle d-none d-md-table-cell">
                                            @if ($user->status)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>Ventas</td>
                                        <td><button class="btn btn-info btn-custom"><i class="fas fa-eye"></i></button>
                                        </td>
                                        <td><button class="btn btn-primary btn-custom"><i
                                                    class="fas fa-edit"></i></button></td>
                                        <td><button class="btn btn-danger btn-custom"><i
                                                    class="fas fa-trash-alt"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class='px-6 py-2'>
                            <p>No hay resultados</p>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
