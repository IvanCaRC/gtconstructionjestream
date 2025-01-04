<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <h1>Crear Nueva Familia</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="submit">
                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control" wire:model="nombre">
                        @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" class="form-control" wire:model="descripcion"></textarea>
                        @error('descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Niveles dinámicos -->
                    @foreach ($niveles as $nivel => $familias)
    <div class="form-group">
        <label for="familia_nivel_{{ $nivel }}">Nivel {{ $nivel }}</label>
        <select id="familia_nivel_{{ $nivel }}" class="form-control" wire:change="calcularSubfamilias($event.target.value, {{ $nivel }})">
            <option value="0" {{ !isset($seleccionadas[$nivel]) || $seleccionadas[$nivel] == 0 ? 'selected' : '' }}>Seleccione una familia</option>
            @foreach ($familias as $familia)
                <option value="{{ $familia->id }}" {{ isset($seleccionadas[$nivel]) && $seleccionadas[$nivel] == $familia->id ? 'selected' : '' }}>
                    {{ $familia->nombre }}
                </option>
            @endforeach
        </select>
    </div>
@endforeach



                    <!-- Botón de envío -->
                    <button type="submit" class="btn btn-primary mt-3">Crear Familia</button>
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
