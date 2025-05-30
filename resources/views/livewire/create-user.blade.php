<div>
    <div class="text-left mb-3">
        <button class="btn btn-custom" wire:click="$set('open', true)"
            style="background-color: #4c72de; color: white;">Agregar Usuario</button>
    </div>
    <x-dialog-modal wire:model="open">
        <x-slot name='title'>
            Registrar Nuevo Usuario
        </x-slot>
        <x-slot name='content'>
            <form>
                <label for="name">Imagen de perfil</label>
                <div class="form-group">
                    <div class="mb-3 d-flex align-items-center">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Imagen"
                                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 10px;">
                        @else
                            <img src="{{ asset('storage/StockImages/stockUser.png') }}" alt="Imagen por Defecto"
                                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 10px;">
                        @endif
                        <label class="btn btn-primary btn-file">
                            Elegir archivo <input type="file" wire:model="image" name="image" accept="image/*">
                        </label>
                    </div>
                    @error('image')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    @if (session('imageError'))
                        <div class="alert alert-danger">{{ session('imageError') }}</div>
                    @endif
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name">Nombre</label>
                        <input type="text" id="name"
                            class="form-control @error('name') required-field @enderror" wire:model.defer="name"
                            wire:blur="validateField('name')">
                        @error('name')
                            <span class="error-message">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <div class="form-group col-md-4">
                        <label for="first_last_name">Primer Apellido</label>
                        <input type="text" id="first_last_name"
                            class="form-control @error('first_last_name') required-field @enderror"
                            wire:model.defer="first_last_name" wire:blur="validateField('first_last_name')">

                        @error('first_last_name')
                            <span class="error-message">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>
                    <div class="form-group col-md-4">
                        <label for="second_last_name">Segundo Apellido</label>
                        <input type="text" id="second_last_name" class="form-control"
                            wire:model.defer="second_last_name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" class="form-control @error('email') required-field @enderror"
                        wire:model.defer="email" wire:blur="validateField('email')">

                    @error('email')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="number">Teléfono</label>
                    <input type="text" id="number" class="form-control" wire:model.defer="number"
                        oninput="validatePhoneInput(this)">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="status">Estado</label>
                        <select id="status" class="form-control" wire:model.defer="status">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>


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
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="text" id="password"
                        class="form-control @error('password') required-field @enderror" wire:model.defer="password"
                        wire:blur="validateField('password')">

                    @error('password')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror

                </div>
            </form>
        </x-slot>
        <x-slot name='footer'>
            <button class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="resetManual"
                wire:loading.attr="disabled">Cancelar</button>
            <button class="btn btn-primary" onclick="confirmSave()">Guardar</button>

        </x-slot>
    </x-dialog-modal>
    <script>
        function confirmSave() {
            // Llamar al método save de Livewire
            @this.call('save').then(response => {
                if (response) {
                    // Mostrar la alerta después de la creación si todo es correcto
                    Swal.fire({
                        title: 'Usuario creado',
                        text: 'El usuario ha sido creado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al crear el usuario.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                });
            });
        }
    </script>

    <style>
        .img-redonda {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }

        .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        .error-message {
            color: red;
        }

        .required-field {
            border-color: red;
        }

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
