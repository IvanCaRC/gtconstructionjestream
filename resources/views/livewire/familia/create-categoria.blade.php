<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <script src="//unpkg.com/alpinejs" defer></script>

    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <h1>Crear Nueva Familia </h1>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <div class="card">
            <div class="card-body">
                <form>
                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                            wire:model="nombre">
                        @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" wire:model="descripcion"></textarea>
                        @error('descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Niveles dinámicos -->
                    @foreach ($niveles as $nivel => $familias)
                        @if (count($familias) > 0)
                            <div class="form-group">
                                <label for="label_familia_nivel_{{ $nivel }}">Nivel {{ $nivel }}</label>
                                <select id="familia_nivel_{{ $nivel }}"
                                    class="form-control @error('seleccionadas.' . $nivel) is-invalid @enderror"
                                    wire:change="calcularSubfamilias($event.target.value, {{ $nivel }})">
                                    <option value="0"
                                        {{ !isset($seleccionadas[$nivel]) || $seleccionadas[$nivel] == 0 ? 'selected' : '' }}>
                                        Seleccione una familia
                                    </option>
                                    @foreach ($familias as $familia)
                                        <option value="{{ $familia->id }}"
                                            {{ isset($seleccionadas[$nivel]) && $seleccionadas[$nivel] == $familia->id ? 'selected' : '' }}>
                                            {{ $familia->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('seleccionadas.' . $nivel)
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    @endforeach

                    <!-- Botón de envío -->
                    <button type="button" onclick="confirmSave()" class="btn btn-primary mt-3">Crear Familia</button>
                </form>

                <!-- Mensaje de éxito -->
                @if (session()->has('message'))
                    <div class="alert alert-success mt-3">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function confirmSave() {
        // Llamar al método update2 de Livewire
        @this.call('save').then(response => {
            if (response) {
                // Mostrar la alerta después de la actualización si todo es correcto
                Swal.fire({
                    title: 'Familia Creada',
                    text: 'La familia ha sido creada exitosamente.',
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
                text: 'Hubo un problema al Crear la familia.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }
</script>
