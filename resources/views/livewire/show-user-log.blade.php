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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Perfil del Usussssario</h2>
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
                    <div class="card-footer text-right">
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <a href="#" class="d-block mb-3"
                        wire:click="$set('open2', true)">Cambiar
                            contraseña</a>
                    </div>
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
                    <div class="form-group col-md-4">
                        <label for="name">Nombre</label>
                        <input type="text" id="name"
                            class="form-control @error('userEdit.name') is-invalid @enderror"
                            wire:model.defer="userEdit.name">
                        @error('userEdit.name')
                            <span class="invalid-feedback">{{ 'Este campo es obligatorio' }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="first_last_name">Primer Apellido</label>
                        <input type="text" id="first_last_name"
                            class="form-control @error('userEdit.first_last_name') is-invalid @enderror"
                            wire:model.defer="userEdit.first_last_name">
                        @error('userEdit.first_last_name')
                            <span class="invalid-feedback">{{ 'Este campo es obligatorio' }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="second_last_name">Segundo Apellido</label>
                        <input type="text" id="second_last_name" class="form-control"
                            wire:model.defer="userEdit.second_last_name">
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
    <x-dialog-modal wire:model="open2">
        <x-slot name='title'>
            Cambiar Contraseña
        </x-slot>
        <x-slot name='content'>
            <form>
                <div class="form-group">
                    <label for="current_password">Contraseña Actual</label>
                    <input type="password" id="current_password" class="form-control"
                        wire:model.defer="current_password">
                    @error('current_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="new_password">Nueva Contraseña</label>
                    <input type="password" id="new_password" class="form-control" wire:model.defer="new_password">
                    @error('new_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Nueva Contraseña</label>
                    <input type="password" id="confirm_password" class="form-control"
                        wire:model.defer="confirm_password">
                    @error('confirm_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </form>
        </x-slot>
        <x-slot name='footer'>
            <button class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="$set('open2',false)"
                wire:loading.attr="disabled">Cancelar</button>
            <button class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                onclick="updatePassword()">Aceptar</button>
        </x-slot>
    </x-dialog-modal>

    <script>
        function updatePassword() {
            // Llamar al método u    pdate2 de Livewire
            @this.call('updatePassword').then(response => {
                if (response) {
                    // Mostrar la alerta después de la actualización si todo es correcto
                    Swal.fire({
                        title: 'Contraseña actualizada',
                        text: 'La contraseña a sido actualisada correctamente',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al actualizar la contraseña.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>
</div>
