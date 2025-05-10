<div>
    <style>
        .fixed-size-img-container {
            width: 200px;
            height: 200px;
            flex-shrink: 0;
            /* No permite que el contenedor se reduzca */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fixed-size-img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            /* Muestra la imagen completa sin recortes */
            border-radius: 50%;
            /* Mantiene la imagen circular */
        }
    </style>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Perfil del Usuario</h2>
                    </div>
                    <div class="card-body d-flex">
                        <div class="mr-3 text-center fixed-size-img-container">
                            @if ($user->image && $user->image !== 'users/')
                                <img src="{{ asset('storage/' . $user->image) }}" alt="Imagen del Usuario"
                                    class="fixed-size-img">
                            @else
                                <img src="{{ asset('storage/StockImages/stockUser.png') }}" alt="Imagen del Usuario"
                                    class="fixed-size-img">
                            @endif
                        </div>
                        <div class="pl-3">
                            <h1 class="mt-2">{{ $user->name }} {{ $user->first_last_name }}
                                {{ $user->second_last_name }}</h1>
                            <a href="#" class="d-block mb-3" wire:click="edit({{ $user->id }})">Editar
                                perfil</a>
                            <h5 class="card-title mt-4 role-description">Rol:
                                @foreach ($user->roles as $role)
                                    {{ $role->name }}
                                @endforeach
                            </h5>
                            <h5 class="card-title mt-3">Estado: {{ $user->status == 1 ? 'Activo' : 'Inactivo' }}</h5>
                            <h5 class="card-title mt-3">Correo: {{ $user->email ?? '' }}</h5>
                            <h5 class="card-title mt-3">Número Telefónico: {{ $user->number ?? '' }}</h5>
                            <h5 class="card-title mt-4 role-description">Obligaciones:
                                @foreach ($user->roles as $role)
                                    {{ $role->description }}
                                @endforeach
                            </h5>
                        </div>
                    </div>
                    @if (auth()->user()->id !== $user->id)
                        <div class="card-footer text-right">
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <a href="#" class="text-danger"
                                onclick="confirmDeletion({{ $user->id }}, '{{ $user->name }}', '{{ $user->first_last_name }}')">Eliminar
                                usuario</a>
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
                                            );
                                        }
                                    });
                                }
                            </script>

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
                        <label class="btn btn-primary">
                            Elegir archivo <input type="file" wire:model="image" name="image"
                                style="display: none;">
                        </label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <h3>{{ $userEdit['name'] }} {{ $userEdit['first_last_name'] }}
                            {{ $userEdit['second_last_name'] }}</h3>
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
                                border-radius: 50%;
                                /* Hace el borde circular */
                                outline: none;
                                /* Elimina el contorno de enfoque */
                            }

                            .form-check-input:checked {
                                border-color: transparent;
                                /* Elimina el contorno al seleccionar */
                                box-shadow: none;
                                /* Evita que se ilumine */
                            }

                            .form-check-input:focus {
                                box-shadow: none;
                                /* Evita que se ilumine al enfocarse */
                            }
                        </style>

                        <div class="form-group col-md-6">
                            <label for="departamento">Departamentos</label>
                            @foreach ($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="role{{ $role->id }}"
                                        wire:model.defer="selectedRoles" value="{{ $role->name }}">
                                    <label class="form-check-label"
                                        for="role{{ $role->id }}">{{ $role->name }}</label>
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
            <button class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="$set('open',false)"
                wire:loading.attr="disabled">Cancelar</button>


            <button class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                onclick="confirmUpdate2()">Actualizar</button>





        </x-slot>
    </x-dialog-modal>
    <script>
        function confirmUpdate2() {
            // Llamar al método update2 de Livewire
            @this.call('update2').then(response => {
                if (response) {
                    // Mostrar la alerta después de la actualización si todo es correcto
                    Swal.fire({
                        title: 'Usuario actualizado',
                        text: 'El usuario ha sido actualizado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al actualizar el usuario.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                });
            });
        }
    </script>

<script>
    function validatePhoneInput(element) {
        // Permitir solo números, espacios y el signo de +
        element.value = element.value.replace(/[^0-9\s+]/g, '');

        // Limitar la longitud a 16 caracteres
        if (element.value.length > 20) {
            element.value = element.value.substring(0, 20);
        }
    }
</script>


</div>
