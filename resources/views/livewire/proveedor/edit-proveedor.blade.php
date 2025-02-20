<div>

    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" class="form-control @error('proveedorEdit.nombre') is-invalid @enderror"
            wire:model.defer="proveedorEdit.nombre">
        @error('proveedorEdit.nombre')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea rows="5" id="descripcion" class="form-control" wire:model.defer="proveedorEdit.descripcion"
            wire:model="descripcion"></textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="correo">Correo</label>
            <input type="email" id="correo"
                class="form-control @error('proveedorEdit.correo') is-invalid @enderror"
                wire:model.defer="proveedorEdit.correo">
            @error('proveedorEdit.correo')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="rfc">RFC</label>
            <input id="rfc" class="form-control @error('proveedorEdit.rfc') is-invalid @enderror"
                wire:model.defer="proveedorEdit.rfc">
            @error('proveedorEdit.rfc')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label>Teléfonos</label>
        @foreach ($telefonos as $index => $telefono)
            <div class="input-group mb-2">
                <input type="text"
                    class="form-control @error('telefonos.' . $index . '.nombre') is-invalid @enderror"
                    wire:model.defer="telefonos.{{ $index }}.nombre" placeholder="Nombre de contacto">
                <input type="text"
                    class="form-control @error('telefonos.' . $index . '.numero') is-invalid @enderror"
                    wire:model.defer="telefonos.{{ $index }}.numero" placeholder="Teléfono" oninput="validatePhoneInput(this)">
                @if ($errors->has('telefonos.' . $index . '.nombre') || $errors->has('telefonos.' . $index . '.numero'))
                    <div class="invalid-feedback">
                        @error('telefonos.' . $index . '.nombre')
                            <div>{{ $message }}</div>
                        @enderror
                        @error('telefonos.' . $index . '.numero')
                            <div>{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                @if ($index > 0)
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger ml-2"
                            wire:click="removeTelefono({{ $index }})">Eliminar</button>
                    </div>
                @endif
            </div>
        @endforeach
        <button type="button" class="btn btn-primary mt-2" wire:click="addTelefono">Agregar otro
            teléfono</button>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="archivosFacturacion">Archivos de facturación</label>
            @if (!$facturacion)
                <div class="file-upload" onclick="document.getElementById('archivosFacturacion').click();">
                    <span class="file-upload-icon">&#x1F4C2;</span>
                    <span class="file-upload-text">Buscar archivos<br>Arrastre y suelte archivos aquí</span>
                    <input type="file" id="archivosFacturacion" class="form-control-file" wire:model="facturacion"
                        accept=".pdf">
                </div>
                <small class="form-text text-muted">Por favor, suba archivos en formato PDF solamente.</small>
            @else
                <div class="form-group">
                    <div class="form-group">
                        <a href="{{ asset('storage/' . $proveedorEdit['archivo_facturacion_pdf']) }}" target="_blank"
                            class="btn btn-secondary">
                            Ver Archivo Actual
                        </a>
                    </div>
                    <div class="file-upload form-group"
                        onclick="document.getElementById('archivosFacturacionCar').click();">
                        <span class="file-upload-icon">&#x1F4C4;</span>
                        <span class="file-upload-text">Archivo Cargado<br>{{ $fileNameFacturacion }}</span>
                        <input type="file" id="archivosFacturacionCar" class="form-control-file"
                            wire:model="facturacion" accept=".pdf">
                    </div>
                    <button type="button" class="btn btn-danger"
                        wire:click="eliminarArchivoFacturacion">Eliminar</button>
                    <small class="form-text text-muted">Por favor, suba archivos en formato PDF
                        solamente.</small>
                </div>
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label for="archivosBancarios">Datos Bancarios</label>
            @if (!$bancarios)
                <div class="file-upload" onclick="document.getElementById('archivosBancarios').click();">
                    <span class="file-upload-icon">&#x1F4C2;</span>
                    <span class="file-upload-text">Buscar archivos<br>Arrastre y suelte archivos aquí</span>
                    <input type="file" id="archivosBancarios" class="form-control-file" wire:model="bancarios"
                        accept=".pdf">
                </div>
                <small class="form-text text-muted">Por favor, suba archivos en formato PDF solamente.</small>
            @else
                <div class="form-group">
                    <div class="form-group">
                        <a href="{{ asset('storage/' . $proveedorEdit['datos_bancarios_pdf']) }}" target="_blank"
                            class="btn btn-secondary">
                            Ver Archivo Actual
                        </a>
                    </div>

                    <div class="file-upload form-group"
                        onclick="document.getElementById('archivosBancariosCar').click();">
                        <span class="file-upload-icon">&#x1F4C4;</span>
                        <span class="file-upload-text">Archivo Cargado<br>{{ $fileNameBancarios }}</span>
                        <input type="file" id="archivosBancariosCar" class="form-control-file"
                            wire:model="bancarios" accept=".pdf">
                    </div>
                    <button type="button" class="btn btn-danger"
                        wire:click="eliminarArchivoBancarios">Eliminar</button>
                    <small class="form-text text-muted">Por favor, suba archivos en formato PDF
                        solamente.</small>
                </div>
            @endif
        </div>
    </div>
    <div class="form-group">
        <h3>Familias</h3>
        <div class="input-group mb-2">
            @if (count($familiasSeleccionadas) > 0)
                @foreach ($familiasSeleccionadas as $index => $familia)
                    <div class="w-100 d-flex align-items-center mb-2">
                        <div class="flex-grow-1">
                            {{ $familia['nombre'] }}
                        </div>
                        <button type="button" wire:click="removeFamilia({{ $index }})"
                            class="btn btn-danger btn-sm ml-2">Eliminar</button>
                    </div>
                @endforeach
            @else
                <div class="no-familias-seleccionadas w-100">
                    No hay familias seleccionadas
                </div>
            @endif
        </div>
        <button type="button" wire:click="$set('openModalFamilias', true)" class="btn btn-primary mt-3">Agregar
            Familia</button>


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
                    wire:model.defer="direccionesAsignadas.{{ $index }}.referencia">
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

    {{-- <button type="button" onclick="confirmUpdate()" class="btn btn-primary mt-3">Actualizar proveedor</button> --}}




    <x-dialog-modal wire:model="openModalFamilias">
        <x-slot name='title'>
            Añadir Familia
        </x-slot>
        <x-slot name='content'>
            <form>
                @foreach ($niveles as $nivel => $familias)
                    @if (count($familias) > 0)
                        <div class="form-group">
                            <label for="label_familia_nivel_{{ $nivel }}">Nivel {{ $nivel }}</label>
                            <select id="familia_nivel_{{ $nivel }}" class="form-control"
                                wire:change="calcularSubfamilias($event.target.value, {{ $nivel }})">
                                <option value="0"
                                    {{ !isset($seleccionadas[$nivel]) || $seleccionadas[$nivel] == 0 ? 'selected' : '' }}>
                                    Seleccione una familia</option>
                                @foreach ($familias as $familia)
                                    <option value="{{ $familia->id }}"
                                        {{ isset($seleccionadas[$nivel]) && $seleccionadas[$nivel] == $familia->id ? 'selected' : '' }}>
                                        {{ $familia->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endforeach
            </form>
        </x-slot>
        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary mr-2 disabled:opacity-50"
                wire:click="$set('openModalFamilias',false)" wire:loading.attr="disabled">Cancelar</button>
            <button type="button" class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                wire:click="confirmFamilia">Agregar familia</button>
        </x-slot>
    </x-dialog-modal>
    {{-- 
    <script>
        function confirmUpdate() {
            // Llamar al método updateProveedor de Livewire
            @this.call('updateProveedor').then(response => {
                if (response) {
                    // Mostrar la alerta después de la actualización si todo es correcto
                    Swal.fire({
                        title: 'Proveedor Actualizado',
                        text: 'El proveedor ha sido actualizado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirigir a la ruta deseada
                            window.location.href = "{{ route('compras.proveedores.viewProveedores') }}";
                        }
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al actualizar el proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script> --}}

    <script>
        function confirmUpdate() {
            @this.call('updateProveedor').then(response => {
                if (response.proveedor_id) {
                    // Guardar el ID del proveedor recién creado en un campo oculto
                    document.getElementById('proveedor-id-input').value = response.proveedor_id;

                    // Convertir las direcciones a formato JSON
                    const directionsJSON = JSON.stringify(savedAddresses);

                    // Asignar el valor al campo oculto
                    document.getElementById('direcciones-input').value = directionsJSON;

                    // Mostrar la alerta después de la creación si todo es correcto
                    Swal.fire({
                        title: 'Proveedor actualizado',
                        text: 'El proveedor ha sido actualizado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Enviar el formulario
                            document.getElementById('proveedor-form').submit();
                        }
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al crear el proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>
    <script>
        function cancelar() {
            // Llamar al método update2 de Livewire
            window.location.href = "{{ route('compras.proveedores.viewProveedores') }}";
        }
    </script>
    <script>
        function validatePhoneInput(element) {
            // Permitir solo números, espacios y el signo de +
            element.value = element.value.replace(/[^0-9\s+]/g, '');
        }
    </script>

</div>
