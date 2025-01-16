<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <h1>Crear Nuevo Proveedor</h1>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control"wire:model.defer="nombre">
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea rows="5" id="descripcion" class="form-control" wire:model="descripcion"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Correo</label>
                        <input type="email" id="email" class="form-control" wire:model="correo"></input>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">RFC</label>
                        <input id="descripcion" class="form-control" wire:model="rfc"></input>
                    </div>
                    <div class="form-group">
                        <label>Teléfonos</label>
                        @foreach ($telefonos as $index => $telefono)
                            <div class="input-group mb-2">
                                <input type="text" class="form-control"
                                    wire:model.defer="telefonos.{{ $index }}" placeholder="Teléfono">
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
                    <div class="form-group"> <label>Familias</label>
                        @foreach ($familiasSeleccionadas as $index => $familiaSeleccionada)
                            <div class="input-group mb-2"> <select class="form-control"
                                    wire:model.defer="familiasSeleccionadas.{{ $index }}">
                                    <option value="">Seleccione una familia</option>
                                    @foreach ($familias as $familia)
                                        <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                                        @endforeach
                                </select>
                                @if ($index > 0)
                                    <div class="input-group-append"> <button type="button" class="btn btn-danger ml-2"
                                            wire:click="removeFamilia({{ $index }})">Eliminar</button> </div>
                                @endif
                            </div>
                        @endforeach <button type="button" class="btn btn-primary mt-2"
                            wire:click="addFamilia">Agregar otra familia</button>
                    </div>
                    <div class="form-group"> <label for="archivosFacturacion">Archivos de facturación</label>
                        @if (!$facturacion)
                            <div class="file-upload" onclick="document.getElementById('archivosFacturacion').click();">
                                <span class="file-upload-icon">&#x1F4C2;</span> <span class="file-upload-text">Buscar
                                    archivos<br>Arrastre y suelte archivos aquí</span> <input type="file"
                                    id="archivosFacturacion" class="form-control-file" wire:model="facturacion"
                                    accept=".pdf">
                            </div> <small class="form-text text-muted">Por favor, suba archivos en
                                formato PDF solamente.</small>
                        @else
                            <div class="form-group">
                                <div class="file-upload"
                                    onclick="document.getElementById('archivosFacturacionCar').click();">
                                    <span class="file-upload-icon">&#x1F4C4;</span>
                                    <span class="file-upload-text">Archivo Cargado<br>{{ $fileNameFacturacion }}</span>
                                    <input type="file" id="archivosFacturacionCar" class="form-control-file"
                                        wire:model="facturacion" accept=".pdf">
                                </div>
                                <small class="form-text text-muted">Por favor, suba archivos en formato
                                    PDF solamente.</small>
                            </div>
                        @endif
                    </div>

                    <div class="form-group"> <label for="archivosFacturacion">Datos Bancarios</label>
                        @if (!$bancarios)
                            <div class="file-upload" onclick="document.getElementById('archivosBancarios').click();">
                                <span class="file-upload-icon">&#x1F4C2;</span> <span class="file-upload-text">Buscar
                                    archivos<br>Arrastre y suelte archivos aquí</span> <input type="file"
                                    id="archivosBancarios" class="form-control-file" wire:model="bancarios"
                                    accept=".pdf">
                            </div> <small class="form-text text-muted">Por favor, suba archivos en
                                formato PDF solamente.</small>
                        @else
                            <div class="form-group">
                                <div class="file-upload"
                                    onclick="document.getElementById('archivosBancariosCar').click();">
                                    <span class="file-upload-icon">&#x1F4C4;</span>
                                    <span class="file-upload-text">Archivo Cargado<br>{{ $fileNameBancarios }}</span>
                                    <input type="file" id="archivosBancariosCar" class="form-control-file"
                                        wire:model="bancarios" accept=".pdf">
                                </div>
                                <small class="form-text text-muted">Por favor, suba archivos en formato
                                    PDFsolamente.</small>
                            </div>
                        @endif
                    </div>

                </form>
                <button type="button" onclick="confirmSave()" class="btn btn-primary mt-3">Crear proveedor</button>
            </div>
        </div>
    </div>
    <x-dialog-modal wire:model="open">
        <x-slot name='title'>
            Añadir Direccion
        </x-slot>
        <x-slot name='content'>
            <form>
                <div class="form-group">
                    <label for="email">Estado</label>
                    <input type="email" id="email"
                        class="form-control @error('userEdit.email') is-invalid @enderror"
                        wire:model.defer="userEdit.email">
                </div>
                <div class="form-group">
                    <label for="number">Ciudad</label>
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
        function confirmSave() {
            // Llamar al método save de Livewire
            @this.call('save').then(response => {
                if (response) {
                    // Mostrar la alerta después de la creación si todo es correcto
                    Swal.fire({
                        title: 'Proveedor creado',
                        text: 'El proveedor ha sido creado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK'
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
</div>
