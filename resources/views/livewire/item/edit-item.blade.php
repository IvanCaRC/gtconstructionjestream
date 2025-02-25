<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div>
        <div>
            <div>
                <div class="card">
                    <div class="card-header">
                        <h2>Edit Ítem {{ $item->nombre }}</h2>
                    </div>
                    <div class="card-body">

                        <div class="container">
                            <div class="row">
                                <!-- Área de carga de la imagen -->
                                <div class="col-md-4 text-center">
                                    <label for="name">Imagen de item</label>
                                    <div class="form-group">

                                        <div class="text-center">
                                            <label class="btn btn-secondary btn-file">
                                                Elegir archivo

                                                <input type="file" wire:model="nuevasImagenes" name="imagen"
                                                    accept="image/*" style="display: none;" multiple>
                                            </label>
                                            @error('nuevasImagenes')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if ($image || $imagenesCargadas)
                                            <div class="form-group text-center mt-3">
                                                @if ($imagenesCargadas)
                                                    @foreach ($imagenesCargadas as $index => $imaCarg)
                                                        <div class="mi-div"
                                                            style="padding: 20px; display: inline-block; position: relative;">
                                                            <img src="{{ asset('storage/' . $imaCarg) }}" alt="Imagen"
                                                                class="imagen-cuadrada">

                                                            <button
                                                                wire:click="eliminarImagenActual({{ $index }})"
                                                                class="btn btn-danger btn-sm"
                                                                style="position: absolute; top: 5px; right: 5px;">
                                                                &times;
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                @if ($image)
                                                    @foreach ($image as $index => $ima)
                                                        <div class="mi-div"
                                                            style="padding: 20px; display: inline-block; position: relative;">
                                                            <img src="{{ $ima->temporaryUrl() }}" alt="Imagen"
                                                                class="imagen-cuadrada">
                                                            <button wire:click="eliminarImagen({{ $index }})"
                                                                class="btn btn-danger btn-sm"
                                                                style="position: absolute; top: 5px; right: 5px;">
                                                                &times;
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                @if (!$image && !$imagenesCargadas)
                                                    <div class="imagen-predeterminada">
                                                        <span class="file-upload-icon">&#128247;</span>
                                                        <span class="file-upload-text">Sube tu imagen con el botón
                                                            "Elegir
                                                            archivo"</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="imagen-predeterminada">
                                                <span class="file-upload-icon">&#128247;</span>
                                                <span class="file-upload-text">Sin imagenes, sube tu imagen con el botón
                                                    "Elegir
                                                    archivo"</span>
                                            </div>
                                        @endif

                                    </div>
                                </div>


                                <!-- Área del formulario -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" id="nombre"
                                            class="form-control @error('itemEdit.nombre') is-invalid @enderror"
                                            wire:model.defer="itemEdit.nombre" required>
                                        @error('itemEdit.nombre')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <textarea id="descripcion" class="form-control @error('itemEspecificoEdit.descripcion') is-invalid @enderror"
                                            wire:model.defer="itemEspecificoEdit.descripcion"></textarea>
                                        @error('itemEspecificoEdit.descripcion')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="marca">Marca</label>
                                        <input type="text" id="marca"
                                            class="form-control @error('itemEspecificoEdit.marca') is-invalid @enderror"
                                            wire:model.defer="itemEspecificoEdit.marca" required>
                                        @error('itemEspecificoEdit.marca')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- Div para agregar cantidad de piezas para mayoreo --}}
                                    <div class="form-group">
                                        <label for="cantidad_piezas_mayoreo" class="mr-2">Cant. Piezas de
                                            Mayoreo</label>
                                        <input id="itemEspecificoEdit.cantidad_piezas_mayoreo"
                                            class="form-control @error('itemEspecificoEdit.cantidad_piezas_mayoreo') is-invalid @enderror"
                                            wire:model.defer="itemEspecificoEdit.cantidad_piezas_mayoreo" required
                                            oninput="validateNumberOnly(this)">
                                        @error('itemEspecificoEdit.cantidad_piezas_mayoreo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- Div para agregar moc --}}
                                    <div class="form-group">
                                        <label for="moc">MOC (Mínimo de venta a cliente)</label>
                                        <input type="text" id="moc" class="form-control"
                                            wire:model.defer="itemEspecificoEdit.moc" required
                                            oninput="validateNumberOnly(this)">
                                        @error('itemEspecificoEdit.moc')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @if ($errors->has('ProvedoresAsignados.*'))
                                        <tr>
                                            <td colspan="6">
                                                <span class="invalid-feedback"
                                                    style="display: block; color: red; font-weight: bold; text-align: center; padding: 10px; border: 2px solid red; border-radius: 5px; background-color: #f8d7da;">
                                                    Debes requisitar todos los campos
                                                    correspondientes al proveedor.
                                                </span>
                                            </td>
                                        </tr>
                                    @endif

                                    <div class="form-group">
                                        <label> Provedor</label>
                                        <div class="input-group mb-2">

                                            @if (count($ProvedoresAsignados) > 0)
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Nombre</th>
                                                            <th>Tiempo Mínimo de Entrega</th>
                                                            <th>Tiempo Máximo de Entrega</th>
                                                            <th>Precio de Compra</th>
                                                            <th>Unidad</th>
                                                            <th></th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($ProvedoresAsignados as $index => $conexion)
                                                            @php
                                                                $conexionObjeto = (object) $conexion;
                                                            @endphp
                                                            <tr>
                                                                <style>
                                                                    .checkbox-btn {
                                                                        display: inline-flex;
                                                                        align-items: center;
                                                                        justify-content: center;
                                                                        width: 24px;
                                                                        height: 24px;
                                                                        border: 2px solid #ccc;
                                                                        border-radius: 4px;
                                                                        background-color: #fff;
                                                                        cursor: pointer;
                                                                        transition: all 0.2s ease;
                                                                    }

                                                                    .checkbox-btn.selected {
                                                                        border-color: #4caf50;
                                                                        background-color: #4caf50;
                                                                        color: #fff;
                                                                    }

                                                                    .checkbox-btn:hover {
                                                                        border-color: #999;
                                                                    }

                                                                    .checkbox-btn.selected:hover {
                                                                        background-color: #45a045;
                                                                        border-color: #45a045;
                                                                    }

                                                                    .checkbox-icon {
                                                                        font-size: 16px;
                                                                    }
                                                                </style>

                                                                <td>
                                                                    <button
                                                                        class="checkbox-btn {{ $conexionObjeto->estado == 1 ? 'selected' : '' }}"
                                                                        wire:click="seleccionarProveedor({{ $index }}, '{{ $conexionObjeto->proveedor_nombre }}')">
                                                                        @if ($conexionObjeto->estado == 1)
                                                                            <span class="checkbox-icon">✓</span>
                                                                        @endif
                                                                    </button>

                                                                </td>
                                                                <td>{{ $conexionObjeto->proveedor_nombre }}</td>

                                                                <td><input step="1"
                                                                        class="form-control @error('ProvedoresAsignados.' . $index . '.tiempo_minimo_entrega') is-invalid @enderror"
                                                                        wire:model.lazy="ProvedoresAsignados.{{ $index }}.tiempo_minimo_entrega"
                                                                        oninput="validateNumberOnly(this)">
                                                                </td>
                                                                <td><input step="1"
                                                                        class="form-control @error('ProvedoresAsignados.' . $index . '.tiempo_maximo_entrega') is-invalid @enderror"
                                                                        wire:model.lazy="ProvedoresAsignados.{{ $index }}.tiempo_maximo_entrega"
                                                                        oninput="validateNumberOnly(this)">
                                                                </td>
                                                                <td><input step="0.01"
                                                                        class="form-control @error('ProvedoresAsignados.' . $index . '.precio_compra') is-invalid @enderror"
                                                                        wire:model.lazy="ProvedoresAsignados.{{ $index }}.precio_compra"
                                                                        wire:keydown='handleKeydown({{ $index }})'
                                                                        oninput="validatePrice(this)">
                                                                </td>
                                                                <td><input step="0.01"
                                                                        class="form-control @error('ProvedoresAsignados.' . $index . '.unidad') is-invalid @enderror"
                                                                        wire:model.lazy="ProvedoresAsignados.{{ $index }}.unidad"
                                                                        wire:keydown='handleKeydownUnidad({{ $index }})'>
                                                                </td>

                                                                <td><button
                                                                        wire:click="eliminarProveedor({{ $index }})"
                                                                        class="btn btn-danger">Eliminar</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="no-familias-seleccionadas w-100">
                                                    No hay provedores seleccionadas
                                                </div>
                                            @endif

                                        </div>
                                        <button href="#" wire:click="montarModalProveedores()"
                                            class="btn btn-secondary mt-3">Agregar provedor</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label>Familias</label>
                                            <div class="input-group mb-2">
                                                @if (count($familiasSeleccionadas) > 0)
                                                    @foreach ($familiasSeleccionadas as $index => $familia)
                                                        <div class="w-100 d-flex align-items-center mb-2">
                                                            <div class="flex-grow-1">
                                                                {{ $familia['nombre'] }}
                                                            </div>
                                                            <button type="button"
                                                                wire:click="removeFamilia({{ $index }})"
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
                                                class="btn btn-secondary mt-3">Agregar Familia</button>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="stock" class="mr-2">Stock Actual del Producto</label>
                                            <input type="text" id="cantidad_piezas_mayoreo" class="form-control"
                                                wire:model.defer="itemEspecificoEdit.stock" required
                                                oninput="validateNumberOnly(this)">

                                        </div>


                                    </div>

                                    @if ($provedorSeleccionadoDeLaTabla)
                                        <label for="">{{ $provedorSeleccionadoDeLaTabla }}</label>
                                        <div>
                                            <h4 for="unidad">Unidad</h4>
                                            <label>{{ $unidadSeleccionadaEnTabla }}</label>
                                        </div>
                                        <label>El parámetro por el que se hacen los cálculos se basan en el proveedor
                                            {{ $provedorSeleccionadoDeLaTabla }}</label>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2 mb-3">
                                                    <label for="porcentaje_venta_mayorista" class="mr-2">% Venta
                                                        Mayorista</label>
                                                    <input step="0.01" id="porcentaje_venta_mayorista"
                                                        class="form-control @error('porcentaje_venta_mayorista') is-invalid @enderror"
                                                        wire:model="porcentaje_venta_mayorista"
                                                        wire:keydown='calcularPrecios' required
                                                        oninput="validatePercentage(this)">
                                                    @error('porcentaje_venta_mayorista')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-2 mb-3">
                                                    <label for="porcentaje_venta_minorista" class="mr-2">% Venta
                                                        Minorista</label>
                                                    <input step="0.01" id="porcentaje_venta_minorista"
                                                        class="form-control @error('porcentaje_venta_minorista') is-invalid @enderror"
                                                        wire:model="porcentaje_venta_minorista"
                                                        wire:keydown='calcularPrecios' required
                                                        oninput="validatePercentage(this)">
                                                    @error('porcentaje_venta_minorista')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="precio_venta_mayorista" class="mr-2"><br>Precio
                                                        Mayorista</label>
                                                    <label
                                                        class="form-control">{{ $precio_venta_mayorista ?? 'N/A' }}</label>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="precio_venta_minorista" class="mr-2"><br>Precio
                                                        Minorista</label>
                                                    <label
                                                        class="form-control">{{ $precio_venta_minorista ?? 'N/A' }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="especificaciones">Especificaciones</label>
                                        <!-- Aquí puedes agregar cualquier contenido adicional que necesites -->
                                    </div>

                                    <div class="form-group">
                                        @foreach ($especificaciones as $index => $especificacion)
                                            <div class="input-group mb-2">
                                                <table class="table" id="especificacionesTable">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enunciado"
                                                                    wire:model.defer="especificaciones.{{ $index }}.enunciado">
                                                            </td>
                                                            <td>
                                                                <textarea rows="3" id="descripcion" class="form-control" placeholder="Concepto"
                                                                    wire:model.defer="especificaciones.{{ $index }}.concepto"></textarea>
                                                            </td>
                                                            <td>
                                                                @if ($index > 0)
                                                                    <div class="input-group-append">
                                                                        <button type="button"
                                                                            class="btn btn-danger ml-2"
                                                                            wire:click="removeLineaTecnica({{ $index }})">Eliminar</button>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                        <button type="button" class="btn btn-secondary mt-2"
                                            wire:click="addLineaTecnica">Agregar Línea</button>
                                    </div>
                                    <div class="form-group"> <label for="archivosFacturacion">Fichas tecnicas o
                                            atributos adicionales</label>
                                        @if (!$ficha_Tecnica_pdf)
                                            <div class="file-upload"
                                                onclick="document.getElementById('ficha_Tecnica_pdf').click();">
                                                <span class="file-upload-icon">&#x1F4C2;</span> <span
                                                    class="file-upload-text">Buscar
                                                    archivos<br>Arrastre y suelte archivos aquí</span> <input
                                                    type="file" id="ficha_Tecnica_pdf" class="form-control-file"
                                                    wire:model="ficha_Tecnica_pdf" accept=".pdf">
                                            </div> <small class="form-text text-muted">Por favor, suba archivos en
                                                formato PDF solamente.</small>
                                        @else
                                            <div class="form-group">

                                                <div class="file-upload"
                                                    onclick="document.getElementById('ficha_Tecnica_pdf_car').click();">
                                                    <span class="file-upload-icon">&#x1F4C4;</span>
                                                    <span class="file-upload-text">Archivo
                                                        Cargado<br>{{ $fileNamePdf }}</span>
                                                    <input type="file" id="ficha_Tecnica_pdf_car"
                                                        class="form-control-file" wire:model="ficha_Tecnica_pdf"
                                                        accept=".pdf">
                                                </div>
                                                <small class="form-text text-muted">Por favor, suba archivos en formato
                                                    PDFsolamente.</small>
                                                <div class="form-group">
                                                    <a href="{{ asset('storage/' . $ficha_Tecnica_pdf_actual) }}"
                                                        target="_blank" class="btn btn-secondary">
                                                        Ver Archivo Actual
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-3"
                                        onclick="cancelar()">Cancelar</button>
                                    <button type="button" onclick="confirmSave()"
                                        class="btn btn-primary mt-3">Actualizar Item</button>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <x-dialog-modal wire:model="openModalProveedores">
        <x-slot name='title'>
            Añadir proveedor
        </x-slot>
        <x-slot name='content'>
            @if (!$seleccionProvedorModal)
                <input type="text" wire:model="searchTerm" class="form-control"
                    placeholder="Ingresa el nombre o RFC del provedor" wire:keydown='actualizarProveedores' />
                @if ($searchTerm)
                    @if ($proveedores->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th>Nombre</th>
                                    <th class="d-none d-md-table-cell" wire:click="" style="cursor: pointer;">
                                        RFC
                                    </th>
                                    <th>Direccion(es)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proveedores as $proveedor)
                                    <tr>
                                        <td>
                                            @if ($proveedor->estado)
                                                <span class="badge badge-success">Actualizado</span>
                                            @else
                                                <span class="badge badge-danger">Desactualizado</span>
                                            @endif
                                        </td>
                                        <td>{{ $proveedor->nombre }}</td>
                                        <td class="d-none d-md-table-cell">{{ $proveedor->rfc }}</td>
                                        <td>{{ $proveedor->direccion }}</td>
                                        <td>
                                            <button class="btn btn-secondary btn-custom"
                                                wire:click="asignarValor({{ $proveedor->id }}, '{{ $proveedor->nombre }}')">Seleccionar</button>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    @else
                        <div class='px-6 py-2'>
                            <p>No hay resultados</p>
                        </div>
                    @endif
                @endif
            @else
                <label for="proveedorNombre">Nombre del Proveedor Selecionado:</label>
                <label id="proveedorNombre">{{ $seleccionProvedorModalNombre }}</label>

                <div class="form-group">
                    <label for="tiempoMinEntrega">Tiempo mínimo de entrega (días)</label>
                    <input type="text" id="tiempoMinEntrega" wire:model="tiempoMinEntrega" wire:taper="number"
                        class="form-control" min="0" placeholder="Ingrese los días mínimos" required wire:keydown='keydownparaboton'
                        oninput="validateNumberOnly(this)">
                </div>

                <div class="form-group">
                    <label for="tiempoMaxEntrega">Tiempo máximo de entrega (días)</label>
                    <input type="text" id="tiempoMaxEntrega" wire:model="tiempoMaxEntrega" class="form-control"
                        min="0" placeholder="Ingrese los días máximos" required wire:keydown='keydownparaboton'
                        oninput="validateNumberOnly(this)">
                </div>

                <div class="form-group">
                    <label for="precioCompra">Precio de compra</label>
                    <input type="text" id="precioCompra" wire:model="precioCompra" class="form-control"
                        min="0" step="0.01" placeholder="Ingrese el precio de compra" required wire:keydown='keydownparaboton'
                        oninput="validatePrice(this)">
                </div>

                <div class="form-group">
                    <label for="unidad">Unidad</label>
                    <select id="unidad" wire:model="unidadSeleccionada " class="form-control" required
                        wire:change="asignarUnidad($event.target.value)">
                        <option value="">Seleccione una unidad</option>
                        <option value="pieza">Pieza</option>
                        <option value="kilo">Kilo</option>
                        <option value="rollo">Rollo</option>
                        <option value="metro">Metro</option>
                        <option value="litro">Litro</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                @if ($unidadSeleccionada === 'otro')
                    <div class="form-group">
                        <label for="unidadPersonalizada">Especifique la unidad</label>
                        <input type="text" id="unidadPersonalizada" wire:model="unidadPersonalizada"
                            class="form-control" placeholder="Ingrese el tipo de unidad"
                            wire:keydown='keydownparaboton'>
                    </div>
                @endif
            @endif
        </x-slot>
        <x-slot name='footer'>
            <button class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="cerrarModalProvedore"
                wire:loading.attr="disabled">Cancelar</button>

            @if (!$seleccionProvedorModal)
                <button class="btn btn-primary disabled:opacity-50" disabled="disabled"
                    wire:click="asignarProvedorArregloProvedor">Agregar Proveedor</button>
            @else
                @if ($tiempoMinEntrega && $tiempoMaxEntrega && $precioCompra)
                    @if ($unidadSeleccionada)
                        @if ($unidadSeleccionada === 'otro')
                            @if ($unidadPersonalizada)
                                <button class="btn btn-primary disabled:opacity-50"
                                    wire:click="asignarProvedorArregloProvedor">Agregar Proveedor</button>
                            @else
                                <button class="btn btn-primary disabled:opacity-50" disabled="disabled"
                                    wire:click="asignarProvedorArregloProvedor">Agregar Proveedor</button>
                            @endif
                        @else
                            <button class="btn btn-primary disabled:opacity-50"
                                wire:click="asignarProvedorArregloProvedor">Agregar Proveedor</button>
                        @endif
                    @else
                        <button class="btn btn-primary disabled:opacity-50" disabled="disabled"
                            wire:click="asignarProvedorArregloProvedor">Agregar Proveedor</button>
                    @endif
                @else
                    <button class="btn btn-primary disabled:opacity-50" disabled="disabled"
                        wire:click="asignarProvedorArregloProvedor">Agregar Proveedor</button>
                @endif

            @endif
            
        </x-slot>
    </x-dialog-modal>

    <script>
        function confirmSave() {
            // Llamar al método save de Livewire
            @this.call('save').then(response => {
                if (response) {
                    // Mostrar la alerta después de la creación si todo es correcto
                    Swal.fire({
                        title: 'Item Actualizado',
                        text: 'El Item ha sido actualizado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirigir a la ruta deseada
                            window.location.href = "{{ route('compras.items.viewItems') }}";
                        }
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al actualizar el item.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>
    <script>
        function cancelar() {
            // Llamar al método update2 de Livewire
            window.location.href = "{{ route('compras.items.viewItems') }}";
        }
    </script>
    <script>
        function validateNumberOnly(element) {
            // Permitir solo números
            element.value = element.value.replace(/[^0-9]/g, '');
        }
    </script>

    <script>
        function validatePrice(element) {
            // Permitir solo números y un punto decimal
            element.value = element.value.replace(/[^0-9.]/g, '');

            // Asegurarse de que solo haya un punto decimal
            if (element.value.split('.').length > 2) {
                element.value = element.value.substring(0, element.value.lastIndexOf('.'));
            }
        }
    </script>

    <script>
        function validatePercentage(element) {
            // Permitir solo números y un punto decimal
            element.value = element.value.replace(/[^0-9.]/g, '');

            // Asegurarse de que solo haya un punto decimal
            if (element.value.split('.').length > 2) {
                element.value = element.value.substring(0, element.value.lastIndexOf('.'));
            }
        }
    </script>

</div>
