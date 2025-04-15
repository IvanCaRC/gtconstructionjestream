<div>
    <style>
        .switch-toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .switch-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #d9534f;
            /* rojo por defecto */
            transition: .4s;
            border-radius: 30px;
        }

        .slider::before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #5cb85c;
            /* verde cuando está activo */
        }

        input:checked+.slider::before {
            transform: translateX(30px);
        }

        table {

            width: 100%;
        }

        .columna-estatica {
            width: 260px;
            /* o el valor que tú quieras */

        }
    </style>
    <h3 class="ml-3">Listas a cotizar del proyecto</h3>
    <div class="card">
        <div class="card-body">
            <div class="text-left mb-3">
                <button class="btn btn-custom" style="background-color: #4c72de; color: white;"
                    wire:click="saveListaNueva">Registrar lista</button>
            </div>
            <div class="row m   b-3">
                <div class="col-md-10">
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar lista...">
                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2">
                        <option value="0">Todos los estados</option>
                        <option value="1">Activo</option>
                        <option value="2">Inactivo</option>
                        <option value="3">Cancelados</option>
                    </select>
                </div>
            </div>
            @if ($listas && $listas->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Listas a cotizar</th>
                            <th></th>
                            <th>Cotizaciones</th>
                            <th>Ordenes de venta</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listas as $lista)
                            </tr>
                            <td>
                                {{-- Badge de estado --}}
                                {{-- {{ $lista->id }} --}}
                                @php
                                    $estados = [
                                        1 => ['label' => 'Activo', 'class' => 'badge-success'],
                                        2 => ['label' => 'Inactivo', 'class' => 'badge-secondary'],
                                        3 => ['label' => 'Cotizando', 'class' => 'badge-warning'],
                                        4 => ['label' => 'Cotizado', 'class' => 'badge-primary'],
                                        5 => ['label' => 'Cancelado', 'class' => 'badge-danger'],
                                    ];

                                    $estado = $estados[$lista->estado] ?? [
                                        'label' => 'Desconocido',
                                        'class' => 'badge-secondary',
                                    ];
                                @endphp

                                <span class="badge {{ $estado['class'] }}">{{ $estado['label'] }}</span>
                                {{ $lista->nombre }}
                            </td>

                            <td class="columna-estatica">
                                {{-- Botones de acción --}}
                                {{ $lista->estado }}
                                @if ($lista->estado != 5)
                                    <button class="btn btn-danger btn-sm" title="Cancelar">Cancelar</button>
                                @endif


                                <button class="btn btn-info btn-sm mr-1" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </button>

                                @if (!in_array($lista->estado, [3, 4, 5]))
                                    <button class="btn btn-primary btn-sm mr-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif


                                @if ($lista->estado == 1 || $lista->estado == 2)
                                    <label class="switch-toggle">
                                        <input type="checkbox" wire:click="toggleEstado({{ $lista->id }})"
                                            {{ $lista->estado == 1 ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                @endif
                            </td>

                            <td>
                                @if ($lista->estado == 1)
                                    <button class="btn btn-custom" style="background-color: #4c72de; color: white;">
                                        Mandar a cotizar
                                    </button>
                                @elseif ($lista->estado == 2)
                                    <label for="">Preguntar si tiene una cotizacion vinculada, si es asi debe
                                        poder verse, si no es asi debe decir que se necesita activar para mandar a
                                        cotizacion</label>
                                @elseif ($lista->estado == 3)
                                    <label for="">Cotizando...</label>
                                @elseif ($lista->estado == 4)
                                    <label for="">Mostrar cotizacion y opciones</label>
                                @elseif ($lista->estado == 5)
                                    <label for="">Preguntar si tiene una cotizacion y mostrarla cancelada. Si
                                        no la tiene simplemente marcar como cancelada.</label>
                                @endif
                            </td>
                            <td>

                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div>
                    No hay listas registradas en este proyecto.
                </div>
            @endif
        </div>
    </div>
</div>
