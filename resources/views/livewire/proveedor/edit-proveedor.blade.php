<div>

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" class="form-control" wire:model.defer="proveedorEdit.nombre"
                        wire:model="nombre">
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea rows="5" id="descripcion" class="form-control" wire:model.defer="proveedorEdit.descripcion"
                        wire:model="descripcion"></textarea>
                </div>

                <div class="form-group">
                    <label for="descripcion">Correo</label>
                    <input type="email" id="email" class="form-control" wire:model.defer="proveedorEdit.correo"
                        wire:model="correo"></input>
                </div>

                <div class="form-group">
                    <label for="descripcion">RFC</label>
                    <input id="descripcion" class="form-control" wire:model.defer="proveedorEdit.rfc"
                        wire:model="rfc"></input>
                </div>
                <div class="form-group">
                    <label>Teléfonos</label>
                    @foreach ($telefonos as $index => $telefono)
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" wire:model.defer="telefonos.{{ $index }}"
                                placeholder="Teléfono">
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
                <div class="form-group">
                    <label for="archivosFacturacion">Archivos de facturación</label>
                    @if (!$facturacion)
                        <div class="file-upload" onclick="document.getElementById('archivosFacturacion').click();">
                            <span class="file-upload-icon">&#x1F4C2;</span>
                            <span class="file-upload-text">Buscar archivos<br>Arrastre y suelte archivos aquí</span>
                            <input type="file" id="archivosFacturacion" class="form-control-file"
                                wire:model="facturacion" accept=".pdf">
                        </div>
                        <small class="form-text text-muted">Por favor, suba archivos en formato PDF solamente.</small>
                    @else
                        <div class="form-group">
                            <div class="form-group">
                                <a href="{{ asset('storage/' . $proveedorEdit['archivo_facturacion_pdf']) }}"
                                target="_blank" class="btn btn-secondary">
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

                <div class="form-group">
                    <label for="archivosBancarios">Datos Bancarios</label>
                    @if (!$bancarios)
                        <div class="file-upload" onclick="document.getElementById('archivosBancarios').click();">
                            <span class="file-upload-icon">&#x1F4C2;</span>
                            <span class="file-upload-text">Buscar archivos<br>Arrastre y suelte archivos aquí</span>
                            <input type="file" id="archivosBancarios" class="form-control-file"
                                wire:model="bancarios" accept=".pdf">
                        </div>
                        <small class="form-text text-muted">Por favor, suba archivos en formato PDF solamente.</small>
                    @else
                        <div class="form-group">
                            <div class="form-group">
                                <a href="{{ asset('storage/' . $proveedorEdit['datos_bancarios_pdf']) }}"
                                    target="_blank" class="btn btn-secondary">
                                    Ver Archivo Actual
                                </a>
                            </div>

                            <div class="file-upload form-group" onclick="document.getElementById('archivosBancariosCar').click();">
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
                    <button href="#" wire:click="$set('openModalFamilias', true)"
                        class="btn btn-primary mt-3">Agregar Familia</button>
                </div>


                    <button type="button" onclick="confirmUpdate()" class="btn btn-primary mt-3">Actualizar proveedor</button>

                


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
            <button class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="$set('openModalFamilias',false)"
                wire:loading.attr="disabled">Cancelar</button>
            <button class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                wire:click="confirmFamilia">Agregar familia</button>
        </x-slot>
    </x-dialog-modal>

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
</script>


</div>
