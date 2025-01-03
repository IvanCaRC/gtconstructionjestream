<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <h1 class="pl-4">Crear Nueva Familia</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="card">
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <form>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control" wire:model="nombre">
                        @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" class="form-control" wire:model="descripcion"></textarea>
                        @error('descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @php
                        $contador = 0;
                    @endphp

                    <select class="form-control" name="familia_nivel_1"
                        wire:change="calcularSubfamilias($event.target.value)">
                        <option value="0">Seleccione una familia</option>
                        @foreach ($familias as $familia)
                            @if ($familia->nivel == 1)
                                @php
                                    $contador++;
                                @endphp
                                <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                            @endif
                        @endforeach
                    </select>

                    <label for="">Total de familias de nivel 1: {{ $contador }}</label>

                    @if (!empty($familiasFiltradas))

                        <h3>Contenido de Familias Filtradas:</h3>
                        @php
                            $contenidoFiltrado = '';
                            foreach ($familiasFiltradas as $nivel => $familias) {
                                
                                    $contenidoFiltrado .= "Nivel {$nivel}:\n";
                                    foreach ($familias as $familia) {
                                        $contenidoFiltrado .= "ID: {$familia['id']}, Nombre: {$familia['nombre']}\n";
                                    }
                                
                            }
                        @endphp
                        <label for="">{{ nl2br(e($contenidoFiltrado)) }}</label>


                        <h3>Familias Filtradas:</h3>
                        @foreach ($familiasFiltradas as $nivel => $familias)
                            <h4>Nivel {{ $nivel }}</h4>
                            @foreach ($familias as $familia)
                                <label for="">{{ $familia['nombre'] }}</label>
                                @include('livewire.familia.categoria', [
                                    'familia' => $familia,
                                    'nivel' => $nivel,
                                ])
                            @endforeach
                        @endforeach
                    @endif

                    <button type="submit" wire:click="submit" class="btn btn-primary mt-3">Crear Familia</button>
                </form>
            </div>
        </div>
    </div>
</div>
