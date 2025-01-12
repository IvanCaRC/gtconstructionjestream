<style>
    .file-upload {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #007bff;
        border-radius: 5px;
        background-color: #f9f9f9;
        padding: 20px;
        cursor: pointer;
    }

    .file-upload .file-upload-icon {
        font-size: 48px;
        color: #007bff;
    }

    .file-upload .file-upload-text {
        margin-left: 15px;
        font-size: 16px;
        color: #007bff;
    }

    .file-upload input[type="file"] {
        display: none;
    }
</style>
<style>
    .btn-round {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        padding: 0;
        font-size: 24px;
        text-align: center;
        line-height: 36px;
    }
</style>
<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <script src="//unpkg.com/alpinejs" defer></script>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <h1>Crear Nuevo Proveedor</h1>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control" wire:model="nombre">
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" class="form-control" wire:model="descripcion"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Correo</label>
                        <input id="descripcion" class="form-control" wire:model="descripcion"></input>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">RFC</label>
                        <input id="descripcion" class="form-control" wire:model="descripcion"></input>
                    </div>

                    <div class="form-group"> <label for="archivosFacturacion">Archivos de facturación</label>
                        <div class="file-upload" onclick="document.getElementById('archivosFacturacion').click();">
                            <span class="file-upload-icon">&#x1F4C2;</span> <span class="file-upload-text">Buscar
                                archivos<br>Arrastre y suelte archivos aquí</span> <input type="file"
                                id="archivosFacturacion" class="form-control-file" wire:model="archivosFacturacion"
                                accept=".pdf">
                        </div> <small class="form-text text-muted">Por favor, suba archivos en
                            formato PDF solamente.</small>
                    </div>

                    <div class="form-group"> <label for="archivosFacturacion">Datos Bancarios</label>
                        <div class="file-upload" onclick="document.getElementById('archivosFacturacion').click();">
                            <span class="file-upload-icon">&#x1F4C2;</span> <span class="file-upload-text">Buscar
                                archivos<br>Arrastre y suelte archivos aquí</span> <input type="file"
                                id="archivosFacturacion" class="form-control-file" wire:model="archivosFacturacion"
                                accept=".pdf">
                        </div> <small class="form-text text-muted">Por favor, suba archivos en
                            formato PDF solamente.</small>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Direccion</label>
                    </div>

                    <div class="form-group">
                        <button type="button" wire:click="$set('open', true)" class="btn btn-primary mt-3">Añadir
                            direccion</button>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="form-group"> <label for="telefono">Teléfono</label> <input type="text"
                                    id="telefono" class="form-control" wire:model="telefono"> </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group"> <button type="button" onclick="confirmSave()"
                                    class="btn btn-primary btn-round">+</button> </div>
                        </div>
                    </div>


                    <button type="button" onclick="confirmSave()" class="btn btn-primary mt-3">Crear proveedor</button>
                </form>
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
</div>
