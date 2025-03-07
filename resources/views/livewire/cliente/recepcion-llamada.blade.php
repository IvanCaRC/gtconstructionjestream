<div>
    <div>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" class="form-control" wire:model.defer="nombre">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="correo">Correo</label>
                <input type="email" id="correo" class="form-control" wire:model="correo">
            </div>

            <div class="col-md-6 mb-3">
                <label for="rfc">RFC</label>
                <input id="rfc" class="form-control" wire:model="rfc">

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
            <label>Cuentas</label>
            @foreach ($cuentas as $index => $cuenta)
                <div class="input-group mb-2">
                    <input type="text" class="form-control" wire:model.defer="cuentas.{{ $index }}.titular"
                        placeholder="Nombre del titular de la cuenta">
                    <input type="text" class="form-control" wire:model.defer="cuentas.{{ $index }}.numero"
                        placeholder="Cuenta" id="telefono">
                    @if ($index > 0)
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger ml-2"
                                wire:click="removeCuenta({{ $index }})">Eliminar</button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="form-group">
            <label>Claves</label>
            @foreach ($claves as $index => $clave)
                <div class="input-group mb-2">
                    <input type="text" class="form-control" wire:model.defer="claves.{{ $index }}.titular"
                        placeholder="Nombre del titular de la clave">
                    <input type="text" class="form-control" wire:model.defer="claves.{{ $index }}.numero"
                        placeholder="Clave" id="telefono">
                    @if ($index > 0)
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger ml-2"
                                wire:click="removeClave({{ $index }})">Eliminar</button>
                        </div>
                    @endif
                </div>
            @endforeach
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
                        title: 'cliente creado',
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
                    text: 'Hubo un problema al crear el clinete, verifica tu formulario.',
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
</div>
