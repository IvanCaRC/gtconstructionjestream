<div>
    <div>
        <div class="form-group">
            {{-- Implementar edicion de usuario asignado al cliente --}}
            @if (Auth::user()->hasRole('Administrador'))
                <div class="form-group">
                    <label for="usuariosVentas">Usuario asignado al cliente:</label>
                    <select wire:model="selectedUser" id="usuariosVentas"
                        class="form-control @error('selectedUser') is-invalid @enderror">
                        <option value="">-- Selecciona un usuario --</option>
                        @foreach ($this->getUsuariosVentas() as $usuario)
                            <option value="{{ $usuario['id'] }}" {{ $selectedUser == $usuario['id'] ? 'selected' : '' }}>
                                {{ $usuario['name'] }} {{ $usuario['first_last_name'] }}
                                {{ $usuario['second_last_name'] }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Mensaje de error si no se selecciona un usuario --}}
                    @error('selectedUser')
                        <span class="text-danger">Asegúrate de asignar un usuario a tu cliente.</span>
                    @enderror
                </div>
            @endif
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" class="form-control @error('clienteEdit.nombre') is-invalid @enderror"
                wire:model.defer="clienteEdit.nombre" wire:blur="validateField('clienteEdit.nombre')">
            @error('clienteEdit.nombre')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="correo">Correo</label>
                <input type="email" id="correo"
                    class="form-control @error('clienteEdit.correo') is-invalid @enderror"
                    wire:model="clienteEdit.correo" wire:blur="validateField('clienteEdit.correo')">
                @error('clienteEdit.correo')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="rfc">RFC</label>
                <input id="rfc" class="form-control @error('clienteEdit.rfc') is-invalid @enderror"
                    wire:model="clienteEdit.rfc" wire:blur="validateField('clienteEdit.rfc')">
                @error('clienteEdit.rfc')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror

                {{-- @if ($rfcDuplicado && $clienteDuplicadoId)
                    <button type="button" class="btn btn-sm btn-outline-info mt-2 d-flex align-items-center gap-1" 
                        wire:click="viewCliente({{ $clienteDuplicadoId }})">
                        <i class="bi bi-eye"></i>
                        Ver registro
                    </button>
                @endif --}}
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
            <label>Cuentas Bancarias</label>
            @foreach ($bancarios as $index => $bancario)
                <div class="input-group mb-2">
                    <div class="d-flex flex-column w-50">
                        <input type="text"
                            class="form-control @error("bancarios.{$index}.banco") is-invalid @enderror"
                            wire:model.defer="bancarios.{{ $index }}.banco"
                            placeholder="Ingresa el nombre del banco">
                        @error("bancarios.{$index}.banco")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex flex-column w-50">
                        <input type="text"
                            class="form-control @error("bancarios.{$index}.titular") is-invalid @enderror"
                            wire:model.defer="bancarios.{{ $index }}.titular"
                            wire:blur="validateField('bancarios.{{ $index }}.titular')"
                            placeholder="Ingresa el titular de la cuenta">
                        @error("bancarios.{$index}.titular")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="input-group mb-2">
                    <div class="d-flex flex-column w-50">
                        <input type="text"
                            class="form-control @error("bancarios.{$index}.cuenta") is-invalid @enderror"
                            wire:model.defer="bancarios.{{ $index }}.cuenta"
                            wire:blur="validateField('bancarios.{{ $index }}.cuenta')"
                            placeholder="Ingresa el número de cuenta">
                        @error("bancarios.{$index}.cuenta")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex flex-column w-50">
                        <input type="text"
                            class="form-control @error("bancarios.{$index}.clave") is-invalid @enderror"
                            wire:model.defer="bancarios.{{ $index }}.clave"
                            wire:blur="validateField('bancarios.{{ $index }}.clave')"
                            placeholder="Ingresa la clave">
                        @error("bancarios.{$index}.clave")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

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

        <label>Direcciones</label>
    @foreach ($direccionesAsignadas as $index => $direccion)
        <div class="row align-items-end">
            <div class="col-md-2 mb-3">
                <label>Calle</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.calle">
            </div>
            <div class="col-md-2 mb-3">
                <label>Número</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.numero">
            </div>
            <div class="col-md-2 mb-3">
                <label>Colonia</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.colonia">
            </div>
            <div class="col-md-2 mb-3">
                <label>Municipio</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.municipio">
            </div>
            <div class="col-md-2 mb-3">
                <label>Ciudad</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.ciudad">
            </div>
            <div class="col-md-2 mb-3">
                <label>Estado</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.estado">
            </div>
            <div class="col-md-2 mb-3">
                <label>Código Postal</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.cp">
            </div>
            <div class="col-md-2 mb-3">
                <label>País</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.pais">
            </div>
            <div class="col-md-2 mb-3">
                <label>Referencia</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.refernecia">
            </div>
            <div class="col-md-2 mb-3">
                <label>Latitud</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.Latitud" oninput="validateCoordinateValue(this)">
            </div>
            <div class="col-md-2 mb-3">
                <label>Longitud</label>
                <input type="text" class="form-control"
                    wire:model.defer="direccionesAsignadas.{{ $index }}.Longitud" oninput="validateCoordinateValue(this)">
            </div>
            <div class="col-md-2 mb-3 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100"
                    wire:click="removeDireccion({{ $index }})">Eliminar</button>
            </div>
        </div>
    @endforeach

    </div>

    <script>
        function confirmUpdate() {
            @this.call('updateCliente').then(response => {
                if (response.cliente_id) {
                    // Guardar el ID del cliente recién creado en un campo oculto
                    // document.getElementById('cliente-id-input').value = response.cliente_id;

                    // Convertir las direcciones a formato JSON
                    // const directionsJSON = JSON.stringify(savedAddresses);

                    // Asignar el valor al campo oculto
                    // document.getElementById('direcciones-input').value = directionsJSON;

                    // Mostrar la alerta después de la creación si todo es correcto
                    Swal.fire({
                        title: 'Cliente actualizado',
                        text: 'El cliente ha sido actualizado correctamente.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('ventas.clientes.gestionClientes') }}";
                        }
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al actualizar el cliente, verifica tu formulario.',
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
        < link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
        rel = "stylesheet" >
    </script>
</div>
