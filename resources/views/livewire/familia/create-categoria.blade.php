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

                    @if (!empty($familias))
                        <div class="form-group">
                            <select id="1" class="form-control" name="familia_nivel_1"
                                wire:change="calcularSubfamilias($event.target.value)">
                                <option value="0">Seleccione una familia</option>
                                @foreach ($familias as $familia)
                                    @if ($familia->nivel == 1)
                                        <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        @if (!empty($familiasFiltradas))
                            @php
                                $primerNivel = null;
                            @endphp
                            <h3>Contenido de Familias Filtradas:</h3>
                            @foreach ($familiasFiltradas as $nivel => $familias)
                                @if ($primerNivel === null)
                                    @php
                                        $primerNivel = $nivel;
                                    @endphp
                                @endif
                                @if ($nivel == $primerNivel)
                                    <select id="{{ $nivel }}" class="form-control" name="familia_nivel_1"
                                        wire:change="calcularSubfamilias($event.target.value)">
                                        <option value="0">Seleccione una familia</option>
                                        @foreach ($familias as $familia)
                                            <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            @endforeach
                            <p>El primer nivel encontrado es: {{ $primerNivel }}</p>

                                    
                        @endif
                    @endif



                    <button type="submit" wire:click="submit" class="btn btn-primary mt-3">Crear Familia</button>
                </form>
            </div>
        </div>
    </div>
</div>
