<div>
    <h2 class="ml-3">Proyectos del cliente</h2>
    <div class="card">
        <div class="card-body">
            <div class="text-left mb-3">
                <button class="btn btn-custom" style="background-color: #4c72de; color: white;" wire:click="$set('openModalCreacionProyecto', true)">Agregar proyecto</button>
            </div>
            <div class="row mb-3">
                <div class="col-md-10">
                    <!-- Input de búsqueda -->
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar proyecto...">

                    <!-- Filtro de Estado -->

                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2">
                        <option value="2">Todos los estados</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                       
                        <th class="d-none d-md-table-cell" wire:click="order('first_last_name')"
                            style="cursor: pointer;">
                            Nombre
                           
                        </th>
                        <th class="d-none d-md-table-cell" wire:click="order('email')" style="cursor: pointer;">
                            Correo
                            
                        </th>
                        <th class="d-none d-md-table-cell" wire:click="order('number')" style="cursor: pointer;">
                            Teléfono
                            
                        </th>
                        <th>Estado</th>
                        <th>Departamento</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>

    <x-dialog-modal wire:model="openModalCreacionProyecto">
        <x-slot name='title'>
            Registrar Nuevo Proyecto
        </x-slot>
        <x-slot name='content'>

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" class="form-control" wire:model.defer="nombre">
            </div>

            <div class="form-group">
                <label for="unidad">Tipo</label>z
                <select id="unidad" wire:model="tipoDeProyectoSelecionado " class="form-control" required
                    wire:change="asignarTipoDeProyecto($event.target.value)">
                    <option value="">Seleccione el tipo de proyecto</option>
                    <option value="1">Suministro</option>
                    <option value="0">Obra</option>
                </select>
            </div>

            @if ($tipoDeProyectoSelecionado === '1')
                <div class="form-group">
                    <div class="form-group"> <label for="archivosFacturacion">Fichas tecnicas o
                            atributos adicionales</label>
                        @if (!$archivoDeListaDeItems)
                            <div class="file-upload" onclick="document.getElementById('archivoDeListaDeItemsPdf').click();">
                                <span class="file-upload-icon">&#x1F4C2;</span>
                                <span class="file-upload-text">Buscar
                                    archivos<br>Arrastre y suelte archivos aquí</span>
                                <input type="file" id="archivoDeListaDeItemsPdf" class="form-control-file"
                                    wire:model="archivoDeListaDeItems" accept=".pdf">
                            </div>
                            <small class="form-text text-muted">Por favor, suba archivos en
                                formato PDF solamente.</small>
                        @else
                            <div class="form-group">
                                <div class="file-upload"
                                    onclick="document.getElementById('archivoDeListaDeItemsPdfCar').click();">
                                    <span class="file-upload-icon">&#x1F4C4;</span>
                                    <span class="file-upload-text">Archivo
                                        Cargado<br>{{ $fileNamePdf }}</span>
                                    <input type="file" id="archivoDeListaDeItemsPdfCar" class="form-control-file"
                                        wire:model="archivoDeListaDeItems" accept=".pdf">
                                </div>
                                <small class="form-text text-muted">Por favor, suba archivos en formato
                                    PDF solamente.</small>
                            </div>
                        @endif
                    </div>
                    <label for="nombre">Ingrese los items a cotizar</label>
                    <textarea id="lista" class="form-control" wire:model.lazy="lista" rows="7"></textarea>

                </div>
                
            @endif
            @if ($tipoDeProyectoSelecionado === '0')
            <label>lo que pasa si es obra</label>
        @endif
        </x-slot>
        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary mr-2 disabled:opacity-50"
                wire:click="$set('openModalProyectos',false)" wire:loading.attr="disabled">Cancelar</button>
            <button type="button" class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                wire:click="">Agregar familia</button>
        </x-slot>
    </x-dialog-modal>
</div>
