<div>
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

</div>
