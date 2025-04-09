<div>

    <x-dialog-modal wire:model="openModalCreacionProyecto">
        <x-slot name='title'>
            Registrar Nuevo Proyecto
        </x-slot>
        <x-slot name='content'>

            <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" class="form-control" wire:model.defer="nombreProyecto">
            </div>

            <div class="form-group">
                <label for="unidad">Preferencia</label>
                <select id="unidad" wire:model="preferencia" class="form-control" 
                    wire:change="asiganrPreferencia($event.target.value)">
                    <option value="">Ninguna</option>
                    <option value="1">Tiempo de entrega</option>
                    <option value="2">Precio</option>
                </select>
                <small class="form-text text-muted">La preferencia del producto facilitara la selección de items en la cotizacion</small>
            <div class="form-group">
            </div>
            
                <label for="unidad">Tipo</label>
                <select id="unidad" wire:model="tipoDeProyectoSelecionado" class="form-control" required
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
                        @if (!$archivoSubido)
                            <div class="file-upload"
                                onclick="document.getElementById('archivoDeListaDeItemsPdf').click();">
                                <span class="file-upload-icon">&#x1F4C2;</span>
                                <span class="file-upload-text">Buscar
                                    archivos<br>Arrastre y suelte archivos aquí</span>
                                <input type="file" id="archivoDeListaDeItemsPdf" class="form-control-file"
                                    wire:model="archivoSubido" accept=".pdf">
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
                                        wire:model="archivoSubido" accept=".pdf">
                                </div>
                                <small class="form-text text-muted">Por favor, suba archivos en formato
                                    PDF solamente.</small>
                            </div>
                        @endif
                    </div>
                    <label for="nombre">Ingrese los items a cotizar</label>
                    <textarea id="lista" class="form-control" wire:model.lazy="listaACotizarTxt" rows="7"></textarea>

                    <label>Direcciones</label>
                    @if ($clienteEspecifico->direcciones->count() > 0)
                        <select name="direccion_seleccionada" id="direccion-select" class="form-control"
                            wire:model="idDireccionParaProyecto" wire:change="asignarDireccion($event.target.value)">
                            <option value="">
                                Selecciona una direccion
                            </option>
                            @foreach ($clienteEspecifico->direcciones as $direccion)
                                <option value="{{ $direccion->id }}"
                                    title="{{ $direccion->estado }}, {{ $direccion->ciudad }}, {{ $direccion->colonia }}, {{ $direccion->calle }} #{{ $direccion->numero }}">
                                    {{ $direccion->estado }}, {{ $direccion->ciudad }}, {{ $direccion->colonia }},
                                    {{ $direccion->calle }} #{{ $direccion->numero }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Por favor, elige una dericcion para el proyecto.</small>
                        <small class="form-text text-muted">(Nota: Estas direcciones son recuperadas del registro del
                            cliente)</small>
                    @else
                        <p>No hay direcciones disponibles.</p>
                    @endif

                </div>
            @endif
            @if ($tipoDeProyectoSelecionado === '0')
                <div class="form-group">
                    <div class="form-group"> <label for="archivosFacturacion">Fichas tecnicas o
                            atributos adicionales</label>
                        @if (!$archivoSubido)
                            <div class="file-upload" onclick="document.getElementById('archivoDeProyecto').click();">
                                <span class="file-upload-icon">&#x1F4C2;</span>
                                <span class="file-upload-text">Buscar
                                    archivos<br>Arrastre y suelte archivos aquí</span>
                                <input type="file" id="archivoDeProyecto" class="form-control-file"
                                    wire:model="archivoSubido" accept=".pdf">
                            </div>
                            <small class="form-text text-muted">Por favor, suba archivos en
                                formato PDF solamente.</small>
                        @else
                            <div class="form-group">
                                <div class="file-upload"
                                    onclick="document.getElementById('archivoDeProyectoCar').click();">
                                    <span class="file-upload-icon">&#x1F4C4;</span>
                                    <span class="file-upload-text">Archivo
                                        Cargado<br>{{ $fileNamePdf }}</span>
                                    <input type="file" id="archivoDeProyectoCar" class="form-control-file"
                                        wire:model="archivoSubido" accept=".pdf">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label>De acuerdo a las imagenes ingresa lo que se te pide</label>
                        <div class="row">
                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <img src="{{ asset('storage/StockImages/aerea.png') }}"
                                    style="width: 40%; height: auto;">
                            </div>

                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <img src="{{ asset('storage/StockImages/frontal.png') }}"
                                    style="width: 70%; height: auto;">
                            </div>
                        </div>
                        <div>

                            @foreach ($datosGenrales as $index => $datosGenral)
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="frente">(A) Frente</label>
                                        <input type="text" id="frente" class="form-control"
                                            wire:model.defer="datosGenrales.{{ $index }}.frente">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="fondo">(B) Fondo</label>
                                        <input type="text" id="fondo" class="form-control"
                                            wire:model.defer="datosGenrales.{{ $index }}.fondo">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="alturaTecho">(C) Altura techo</label>
                                        <input type="text" id="alturaTecho" class="form-control"
                                            wire:model.defer="datosGenrales.{{ $index }}.alturaTecho">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="areaTotal">Area total</label>
                                        <input type="text" id="areaTotal" class="form-control"
                                            wire:model.defer="datosGenrales.{{ $index }}.areaTotal">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="alturaMuros">Altura de muros</label>
                                        <input type="text" id="alturaMuros" class="form-control"
                                            wire:model.defer="datosGenrales.{{ $index }}.alturaMuros">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="canalon">Canalón</label>
                                        <input type="text" id="canalon" class="form-control"
                                            wire:model.defer="datosGenrales.{{ $index }}.canalon">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="perimetral">Perimetral</label>
                                        <input type="text" id="perimetral" class="form-control"
                                            wire:model.defer="datosGenrales.{{ $index }}.perimetral">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="caballete">Caballete</label>
                                        <input type="text" id="caballete" class="form-control"
                                            wire:model.defer="datosGenrales.{{ $index }}.caballete">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <br>
                    <div>
                        <label>Elementos adicionales </label>
                        @foreach ($adicionales as $index => $adicional)
                            <div class="input-group mb-2">

                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" placeholder="Elemento"
                                            wire:model.defer="adicionales.{{ $index }}.estructura">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" id="descripcion" class="form-control"
                                            placeholder="Cantidad"
                                            wire:model.defer="adicionales.{{ $index }}.cantidad"></input>
                                    </div>
                                    <div class="col-md-1">
                                        @if ($index > 0)
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger ml-2"
                                                    wire:click="removeAdicionales({{ $index }})">Eliminar</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button type="button" class="btn btn-secondary mt-2" wire:click="addAdicionales">Agregar
                            Línea</button>
                    </div>
                    <br>
                    <label>Direcciones</label>
                    @if ($clienteEspecifico->direcciones->count() > 0)
                        <select name="direccion_seleccionada" id="direccion-select" class="form-control"
                            wire:model="idDireccionParaProyecto" wire:change="asignarDireccion($event.target.value)">
                            <option value="">
                                Selecciona una direccion
                            </option>
                            @foreach ($clienteEspecifico->direcciones as $direccion)
                                <option value="{{ $direccion->id }}"
                                    title="{{ $direccion->estado }}, {{ $direccion->ciudad }}, {{ $direccion->colonia }}, {{ $direccion->calle }} #{{ $direccion->numero }}">
                                    {{ $direccion->estado }}, {{ $direccion->ciudad }}, {{ $direccion->colonia }},
                                    {{ $direccion->calle }} #{{ $direccion->numero }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Por favor, elige una dericcion para el proyecto.</small>
                        <small class="form-text text-muted">(Nota: Estas direcciones son recuperadas del
                            cliente)</small>
                    @else
                        <p>No hay direcciones disponibles.</p>
                    @endif

                </div>
            @endif
        </x-slot>
        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="cancelar"
                wire:loading.attr="disabled">Cancelar</button>
            <button type="button" class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                wire:click="saveProyecto">Agregar proyecto</button>
        </x-slot>
    </x-dialog-modal>
</div>
