<li class="form-group" style="padding-left: {{ $nivel * 7 }}px;">
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

        .toggle-btn {
            cursor: pointer;
            margin-right: 5px;
            color: inherit; /* Asegúrate de que el color del texto sea heredado */
        }

        .subfamilias {
            display: none;
        }

        .subfamilias.visible {
            display: block;
        }
        .fas {
    color: inherit; /* Heredar el color del texto circundante */
}

    </style>
    <div class="row form-group">
        @if ($familia->subfamiliasRecursivas->count() > 0)
            <span class="toggle-btn"
                  wire:click="toggleDesplegable({{ $familia->id }})">
                  {!! isset($desplegables[$familia->id]) && $desplegables[$familia->id] ? '<i class="fas fa-chevron-down"></i>' : '<i class="fas fa-chevron-right"></i>' !!}
            </span>
        @endif
        <button class="checkbox-btn {{ in_array($familia->id, $familiasSeleccionadas) ? 'selected' : '' }}"
            wire:click="seleccionarFamilia({{ $familia->id }})"
            style="margin-right: 10px;">
            @if (in_array($familia->id, $familiasSeleccionadas))
                <span class="checkbox-icon">✓</span>
            @endif
        </button>
        {{ $familia->nombre }}
    </div>

    @if ($familia->subfamiliasRecursivas->count() > 0)
        <ul class="subfamilias {{ isset($desplegables[$familia->id]) && $desplegables[$familia->id] ? 'visible' : '' }}">
            @foreach ($familia->subfamiliasRecursivas as $subfamilia)
                @include('livewire.familia.lista-categorias', [
                    'familia' => $subfamilia,
                    'nivel' => $nivel + 1,
                ])
            @endforeach
        </ul>
    @endif
</li>
