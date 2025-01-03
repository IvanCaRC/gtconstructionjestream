@php
    $contador = 0;
@endphp
<select class="form-control" name="familia_nivel_1">
    <option value="">Seleccione una familia</option>
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
