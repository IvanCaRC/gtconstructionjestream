<div>
    <div>
        <div class="form-group">
            {{-- Seleccionar usuario para cargar el cliente --}}
            @if (Auth::user()->hasRole('Administrador'))
                <div class="form-group">
                    <label for="usuariosVentas">Usuario asignado para este cliente:</label>
                    <select wire:model="selectedUser" id="usuariosVentas"
                        class="form-control @error('selectedUser') is-invalid @enderror">
                        @foreach ($this->getUsuariosVentas() as $usuario)
                            <option value="{{ $usuario['id'] }}" {{ $selectedUser == $usuario['id'] ? 'selected' : '' }}>
                                {{ $usuario['name'] }} {{ $usuario['first_last_name'] }}
                                {{ $usuario['second_last_name'] }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Muestra el mensaje de error si el usuario no selecciona un usuario --}}
                    @error('selectedUser')
                        <span class="text-danger">Asegúrate de asignar un usuario a tu cliente.</span>
                    @enderror
                </div>
            @endif
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                wire:model.defer="nombre" wire:blur="validateField('nombre')">
            @error('nombre')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="correo">Correo</label>
                <input type="email" id="correo" class="form-control @error('correo') is-invalid @enderror"
                    wire:model="correo" wire:blur="validateField('correo')">
                @error('correo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="rfc">RFC</label>
                <input id="rfc" class="form-control @error('rfc') is-invalid @enderror" wire:model="rfc"
                    wire:blur="validateField('rfc')">

                @error('rfc')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                @if ($rfcDuplicado && $clienteDuplicadoId)
                    @if ($clienteUsuarioId === auth()->id())
                        <button type="button" class="btn btn-sm btn-outline-info mt-2 d-flex align-items-center gap-1"
                            wire:click="viewCliente({{ $clienteDuplicadoId }})">
                            <i class="bi bi-eye"></i>
                            Ver registro
                        </button>
                    @else
                        <button type="button"
                            class="btn btn-sm btn-outline-warning mt-2 d-flex align-items-center gap-1"
                            onclick="showSecurityAlert()">
                            <i class="bi bi-exclamation-triangle"></i>
                            Registro no concedido
                        </button>
                    @endif
                @endif
            </div>

        </div>

        <div class="form-group">
            <label>Teléfonos</label>
            @foreach ($telefonos as $index => $telefono)
                <div class="input-group mb-2">
                    <input type="text" class="form-control" wire:model.defer="telefonos.{{ $index }}.nombre"
                        placeholder="Nombre de contacto">
                    <input type="text" class="form-control" wire:model.defer="telefonos.{{ $index }}.numero"
                        placeholder="Teléfono" id="telefono" oninput="validatePhoneInput(this)">
                    @if ($index > 0)
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger ml-2"
                                wire:click="removeTelefono({{ $index }})">Eliminar</button>
                        </div>
                    @endif
                </div>
            @endforeach
            <button type="button" class="btn btn-secondary mt-2" wire:click="addTelefono">Agregar otro
                teléfono</button>
        </div>

        <div class="form-group">
            <label>Cuentas Bancaria</label>
            @foreach ($bancarios as $index => $bancario)
                <div class="input-group mb-2">
                    <input type="text" class="form-control" wire:model.defer="bancarios.{{ $index }}.banco"
                        placeholder="Ingresa el nombre del banco">
                    <input type="text" class="form-control" wire:model.defer="bancarios.{{ $index }}.titular"
                        placeholder="Ingresa el titular de la cuenta">
                </div>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" wire:model.defer="bancarios.{{ $index }}.cuenta"
                        placeholder="Ingresa el numero de cuenta">
                    <input type="text" class="form-control" wire:model.defer="bancarios.{{ $index }}.clave"
                        placeholder="Ingresa la clave">
                    @if ($index > 0)
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger ml-2"
                                wire:click="removeBancarios({{ $index }})">Eliminar</button>
                        </div>
                    @endif
                </div>
            @endforeach
            <button type="button" class="btn btn-secondary mt-2" wire:click="addBancarios">Agregar otra cuenta</button>
        </div>
    </div>

    <script>
        function confirmSave() {
            @this.call('save').then(response => {
                if (response.cliente_id) {
                    // Guardar el ID del proveedor recién creado en un campo oculto
                    document.getElementById('cliente-id-input').value = response.cliente_id;

                    // Convertir las direcciones a formato JSON
                    const directionsJSON = JSON.stringify(savedAddresses);

                    // Asignar el valor al campo oculto
                    document.getElementById('direcciones-input').value = directionsJSON;

                    // Mostrar la alerta después de la creación si todo es correcto
                    Swal.fire({
                        title: 'Cliente registrado',
                        text: 'El cliente ha sido creado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Enviar el formulario
                            document.getElementById('proveedor-formee').submit();


                        }
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al crear el cliente, verifica tu formulario.',
                    icon: 'error',
                    confirmButtonText: 'OK'
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
    <script>
        function cancelar() {
            // Llamar al método update2 de Livewire
            window.location.href = "{{ route('ventas.clientes.gestionClientes') }}";
        }
    </script>
    <script>
        function showSecurityAlert() {
            Swal.fire({
                title: 'Acceso restringido',
                html: 'Este registro ya existe en el sistema y pertenece a otro usuario.<br><br><strong>Consúltalo con tu administrador.</strong>',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                allowOutsideClick: false
            });
        }
    </script>
    <script>
        < link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
        rel = "stylesheet" >
    </script>
</div>
