<div>
    <h1 class="pl-4">Usuarios registrados</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-12">
        <div class="card">
            <div class="card-body">
                @livewire('create-user')
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <div class="table-responsive">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Input de búsqueda -->
                        <input type="text" class="form-control mr-2" id="searchInput" wire:model='searchTerm' wire:keydown='search' placeholder="Buscar usuario...">
                        
                        <!-- Filtro de Estado -->
                        <select class="form-control mr-2" wire:model="statusFiltroDeBusqueda" wire:change="filter">
                            <option value="2">Todos los estados</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        
                        <!-- Filtro de Roles -->
                        <select class="form-control mr-2" wire:model="roleFiltroDeBusqueda" wire:change="filter">
                            <option value="">Todos los roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
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
                                        <td class="align-middle">
                                            @if ($user->image && $user->image !== 'users/')
                                                <img src="{{ asset('storage/' . $user->image) }}" alt="Imagen"
                                                    style="width: 50px; height: 50px; border-radius: 50%;">
                                            @else
                                                <img src="{{ asset('storage/StockImages/stockUser.png') }}"
                                                    alt="Imagen por Defecto"
                                                    style="width: 50px; height: 50px; border-radius: 50%;">
                                            @endif
                                        </td>
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
                                        <td>
                                            @foreach ($user->roles as $role)
                                                {{ $role->name }}
                                            @endforeach
                                        </td>
                                        @if (auth()->user()->id !== $user->id)
                                            <td>
                                                <button class="btn btn-info btn-custom"
                                                    wire:click="viewUser({{ $user->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>

                                            <td>
                                                <button class="btn btn-primary btn-custom"
                                                    wire:click="edit({{ $user->id }})"><i
                                                        class="fas fa-edit"></i></button>
                                            </td>
                                        
                                            <td><button class="btn btn-danger btn-custom"
                                                    onclick="confirmDeletion({{ $user->id }}, '{{ $user->name }}', '{{ $user->first_last_name }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <script>
                                                    function confirmDeletion(userId, userName, userFirstLastName) {
                                                        Swal.fire({
                                                            title: `¿Estás seguro de que deseas eliminar a ${userName} ${userFirstLastName}?`,
                                                            text: "¡No podrás revertir esto!",
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#d33',
                                                            cancelButtonColor: '#3085d6',
                                                            confirmButtonText: 'Sí, eliminar',
                                                            cancelButtonText: 'Cancelar'
                                                        }).then((result) => {

                                                            if (result.isConfirmed) {
                                                                @this.call('eliminar', userId);
                                                                Swal.fire(
                                                                    'Eliminado!',
                                                                    `${userName} ${userFirstLastName} ha sido eliminado.`,
                                                                    'success'
                                                                )
                                                            }
                                                        })
                                                    }
                                                </script>

                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class='px-6 py-2'>
                            <p>No hay resultados</p>
                        </div>

                    @endif
                    @if ($users->hasPages())
                        <div class="px-6 py-3">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="open">
        <x-slot name='title'>
            Editar Usuario
        </x-slot>
        <x-slot name='content'>
            <form>
                <label for="name">Imagen de usuario</label>
                <div class="form-group">
                    <div class="mb-3 d-flex align-items-center">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Imagen"
                                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                        @elseif ($userEdit['image'] && $userEdit['image'] !== 'users/')
                            <img src="{{ asset('storage/' . $userEdit['image']) }}" alt="Imagen"
                                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                        @else
                            <img src="{{ asset('storage/StockImages/stockUser.png') }}" alt="Imagen por Defecto"
                                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                        @endif
                        <label class="btn btn-primary btn-file">
                            Elegir archivo <input type="file" wire:model="image" name="image" accept="image/*">
                        </label>
                    </div>
                    @error('image')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <h3>{{ $userEdit['name'] }} {{ $userEdit['first_last_name'] }} {{ $userEdit['second_last_name'] }}</h3>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email"
                        class="form-control @error('userEdit.email') is-invalid @enderror"
                        wire:model.defer="userEdit.email">
                    @error('userEdit.email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="number">Teléfono</label>
                    <input type="text" id="number" class="form-control" wire:model.defer="userEdit.number">
                </div>
                @if ($userEdit['id'] != $currentUserId)
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="status">Estado</label>
                            <select id="status" class="form-control" wire:model.defer="userEdit.status">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <style>
    .form-check-input {
        border-radius: 50%; /* Hace el borde circular */
        outline: none; /* Elimina el contorno de enfoque */
    }
    .form-check-input:checked {
        border-color: transparent; /* Elimina el contorno al seleccionar */
        box-shadow: none; /* Evita que se ilumine */
    }
    .form-check-input:focus {
        box-shadow: none; /* Evita que se ilumine al enfocarse */
    }
</style>

<div class="form-group col-md-6">
    <label for="departamento">Departamentos</label>
    @foreach ($roles as $role)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="role{{ $role->id }}" wire:model.defer="selectedRoles" value="{{ $role->name }}">
            <label class="form-check-label" for="role{{ $role->id }}">{{ $role->name }}</label>
        </div>
    @endforeach
    @error('selectedRoles')
        <span class="error-message">{{ $message }}</span>
    @enderror
</div>

                    </div>
                @endif
            </form>
        </x-slot>
        <x-slot name='footer'>
            <button class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="resetManual"
                wire:loading.attr="disabled">Cancelar</button>
            <button class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                onclick="confirmUpdate()">Actualizar</button>


            <script>
                function confirmUpdate() {
                    // Llamar al método update de Livewire
                    @this.call('update').then(response => {
                        if (response) {
                            // Mostrar la alerta después de la actualización si todo es correcto
                            Swal.fire({
                                title: 'Usuario actualizado',
                                text: 'El usuario ha sido actualizado exitosamente.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        }
                    }).catch(error => {
                        // Manejar error si es necesario
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al actualizar el usuario.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            </script>



        </x-slot>
    </x-dialog-modal>

</div>
