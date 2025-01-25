<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <div>
        <div>
            <div>
                <div class="card">
                    <div class="card-header">
                        <h2>Crear Nuevo Ítem</h2>
                    </div>
                    <div class="card-body">

                        <div class="container">
                            <div class="row">
                                <!-- Área de carga de la imagen -->
                                <div class="col-md-4" class="form-group text-center">
                                    <label for="name">Imagen de item</label>
                                    <div class="form-group">
                                        <div class="text-center">
                                            <label class="btn btn-secondary btn-file">
                                                Elegir archivo
                                                <input type="file" wire:model="image" name="imagen" accept="image/*"
                                                    style="display: none;" multiple>
                                            </label>
                                            @error('image')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="text-center">
                                            @if ($image)
                                                <button href="#" wire:click="eliminarImagenes()"
                                                    class="btn btn-danger mt-3">Eliminar Imagenes</button>
                                            @endif
                                        </div>
                                        <div class="form-group text-center">
                                            @if ($image)

                                                @foreach ($image as $ima)
                                                    <div class="mi-div" style="padding: 20px;">
                                                        <img src="{{ $ima->temporaryUrl() }}" alt="Imagen"
                                                            class="imagen-cuadrada">
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="imagen-predeterminada">
                                                    <span class="file-upload-icon">&#128247;</span>
                                                    <span class="file-upload-text">Sube tu imagen con el botón "Elegir
                                                        archivo"</span>
                                                </div>
                                            @endif
                                            @error('image')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Área del formulario -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" id="nombre" class="form-control" wire:model="nombre"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <textarea id="descripcion" class="form-control" wire:model="descripcion"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="marca">Marca</label>
                                        <input type="text" id="marca" class="form-control" wire:model="marca"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label> Provedor</label>
                                        <div class="input-group mb-2">

                                            <div class="no-familias-seleccionadas w-100">
                                                No hay familias seleccionadas
                                            </div>

                                        </div>
                                        <button href="#" wire:click="montarModalProveedores()"
                                            class="btn btn-secondary mt-3">Agregar provedor</button>
                                    </div>
                                    <div class="form-group">
                                        <label>Familias</label>
                                        <div class="input-group mb-2">
                                            @if (count($familiasSeleccionadas) > 0)
                                                @foreach ($familiasSeleccionadas as $index => $familia)
                                                    <div class="w-100 d-flex align-items-center mb-2">
                                                        <div class="flex-grow-1">
                                                            {{ $familia->nombre }}
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
                                    <div class="form-group">
                                        <label for="unidad">Unidad</label>
                                        <label>{{ $unidad }}</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2 mb-3">
                                                <label for="cantidad_piezas_mayoreo" class="mr-2">Cant. Piezas
                                                    Mayoreo</label>
                                                <input type="number" id="cantidad_piezas_mayoreo" class="form-control"
                                                    wire:model="pz_Mayoreo" required>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="cantidad_piezas_minorista" class="mr-2">Cant. Piezas
                                                    Minorista</label>
                                                <input type="number" id="cantidad_piezas_minorista"
                                                    class="form-control" wire:model="pz_Minorista" required>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="porcentaje_venta_mayorista" class="mr-2">% Venta
                                                    Mayorista</label>
                                                <input type="number" step="0.01" id="porcentaje_venta_mayorista"
                                                    class="form-control" wire:model="porcentaje_venta_mayorista"
                                                    required>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="porcentaje_venta_minorista" class="mr-2">% Venta
                                                    Minorista</label>
                                                <input type="number" step="0.01" id="porcentaje_venta_minorista"
                                                    class="form-control" wire:model="porcentaje_venta_minorista"
                                                    required>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="precio_venta_mayorista" class="mr-2">Precio
                                                    Mayorista</label>
                                                <label>{{ $precio_venta_mayorista }}Aqui va </label>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="precio_venta_minorista" class="mr-2">Precio
                                                    Minorista</label>
                                                <label>{{ $precio_venta_minorista }}Aqui Va </label>
                                            </div>
                                        </div>
                                    </div>

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
                                            </div>
                                        @endif
                                    </div>

                                    <button type="button" onclick="confirmSave()" class="btn btn-primary mt-3">Crear
                                        item</button>
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
                {{-- <table class="table">
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
                                <td class="align-middle d-none d-md-table-cell">
                                    @if ($proveedor->estado)
                                        <span class="badge badge-success">Actualizado</span>
                                    @else
                                        <span class="badge badge-danger">Desactualizado</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $proveedor->nombre }}</td>
                                <td class="align-middle d-none d-md-table-cell">{{ $proveedor->rfc }}</td>
                                <td class="align-middle">
                                    @if ($proveedor->direcciones)
                                        @foreach ($proveedor->direcciones as $direccion)
                                            {{ $direccion->ciudad }}
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td><button class="btn btn-danger btn-custom"
                                       >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table> --}}
            @else
            @endif
        </x-slot>
        <x-slot name='footer'>
            <button class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="$set('openModalProveedores',false)"
                wire:loading.attr="disabled">Cancelar</button>
            <button class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                wire:click="confirmFamilia">Agregar familia</button>
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
                    text: 'Hubo un problema al crear el proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>

</div>
