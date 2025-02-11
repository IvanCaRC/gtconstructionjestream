<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <h1>Editar Familia {{ $familia->nombre }}</h1>
        <div class="card">
            <div class="card-body">
                <form id="familiaForm">
                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre"
                            class="form-control @error('familiaEdit.nombre') is-invalid @enderror"
                            wire:model.defer="familiaEdit.nombre">
                        @error('familiaEdit.nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <span id="nombreError" class="invalid-feedback" style="display:none;">Asigne nombre para
                            actualizar la familia.</span>
                    </div>
                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" class="form-control @error('familiaEdit.descripcion') is-invalid @enderror"
                            wire:model.defer="familiaEdit.descripcion"></textarea>
                        @error('familiaEdit.descripcion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Niveles dinámicos -->
                    @foreach ($niveles as $nivel => $familias)
                        @if (count($familias) > 0)
                            <div class="form-group">
                                <label for="familia_nivel_{{ $nivel }}">Nivel {{ $nivel }}</label>
                                <select id="familia_nivel_{{ $nivel }}"
                                    class="form-control @error('seleccionadas.' . $nivel) is-invalid @enderror"
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
                                @error('seleccionadas.' . $nivel)
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    @endforeach
                    <!-- Botón de envío -->
                    {{--     --}}
                    <button type="button" class="btn btn-secondary" onclick="cancelar()">Cancelar</button>
                    <button type="button" class="btn btn-primary disabled:opacity-50"
                        onclick="validateForm()">Actualizar</button>
                </form>
                <!-- Mensaje de éxito -->
            </div>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        const nombre = document.getElementById('nombre').value;
        const nombreInput = document.getElementById('nombre');
        const nombreError = document.getElementById('nombreError');

        if (!nombre) {
            nombreError.style.display = 'block';
            nombreInput.classList.add('is-invalid');
            return;
        } else {
            nombreError.style.display = 'none';
            nombreInput.classList.remove('is-invalid');
        }

        // Llamar al método update de Livewire
        @this.call('update').then(response => {
            if (response) {
                // Mostrar la alerta después de la actualización si todo es correcto
                Swal.fire({
                    title: 'Familia Actualizada Correctamente',
                    text: 'La familia a sido actualizada exitosamente.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false // Deshabilitar el clic fuera para cerrar
                }).then((result) => {
                    // Redirigir al hacer clic en el botón "OK"
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('compras.familias.viewFamilias') }}";
                    }
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
<script>
    function cancelar() {
        // Llamar al método update2 de Livewire
        window.location.href = "{{ route('compras.familias.viewFamilias') }}";
    }
</script>
