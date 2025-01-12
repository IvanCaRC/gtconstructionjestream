<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <h1>Editar Familia {{ $familia->nombre }}</h1>
        <div class="card">
            <div class="card-body">
                <form>
                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control" wire:model="nombre"
                            wire:model.defer="familiaEdit.nombre">
                    </div>
                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" class="form-control" wire:model.defer="familiaEdit.descripcion"></textarea>
                    </div>
                    <!-- Niveles dinámicos -->
                    @foreach ($niveles as $nivel => $familias)
                        @if (count($familias) > 0)
                            <div class="form-group">
                                <label for="familia_nivel_{{ $nivel }}">Nivel {{ $nivel }}</label>
                                <select id="familia_nivel_{{ $nivel }}" class="form-control"
                                    wire:change="calcularSubfamilias($event.target.value, {{ $nivel }})">
                                    <option value="0"
                                        {{ !isset($seleccionadas[$nivel]) || $seleccionadas[$nivel] == 0 ? 'selected' : '' }}>
                                        Seleccione una familia
                                    </option>
                                    @foreach ($familias as $familia)
                                        @if ($familiaActual != $familia)
                                            <option value="{{ $familia->id }}"
                                                {{ isset($seleccionadas[$nivel]) && $seleccionadas[$nivel] == $familia->id ? 'selected' : '' }}>
                                                {{ $familia->nombre }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endforeach
                    <!-- Botón de envío -->
                    <button class="btn btn-secondary mr-2 disabled:opacity-50">Cancelar</button>
                    <button type="button" class="btn btn-primary disabled:opacity-50" onclick="confirmUpdate()">Actualizar</button>
                </form>
                <!-- Mensaje de éxito -->
            </div>
        </div>
    </div>
</div>

<script>
    function confirmUpdate() {
        // Llamar al método update de Livewire
        @this.call('update').then(response => {
            if (response) {
                // Mostrar la alerta después de la actualización si todo es correcto
                Swal.fire({
                    title: 'Familia Actualizada Correctamente',
                    text: 'La familia a sido actualizada exitosamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        }).catch(error => {
            // Manejar error si es necesario
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al actualizar la familia.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }
</script>
