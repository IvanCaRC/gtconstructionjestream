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
            width: 200px;
            /* o el valor que tú quieras */

        }

        .columna-estatica-columna {
            width: 250px;
            /* o el valor que tú quieras */

        }

        .columna-estatica-numero1 {
            width: 140px;
            /* o el valor que tú quieras */

        }

        .columna-estatica-estado {
            width: 90px;
            /* o el valor que tú quieras */

        }
    </style>
    <h3 class="ml-3">Listas a cotizar del proyecto</h3>
    <div class="card">
        <div class="card-body">
            <div class="text-left mb-3">
                <button class="btn btn-custom" style="background-color: #4c72de; color: white;"
                    wire:click="saveListaNueva"  @if($proyecto->estado !== 1) disabled @endif>Registrar lista</button>
            </div>


            <div class="row m   b-3">
                <div class="col-md-10">
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar lista..."
                        wire:model='searchTerm' wire:keydown='search'>
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model="statusFiltro" wire:change="filter">
                        <option value="0">Todos los estados</option>
                        <option value="1">Creando Lista</option>
                        <option value="2">Creando cotizacion</option>
                        <option value="3">Cotizado</option>
                        <option value="4">Esperandopago</option>
                        <option value="5">Pagado</option>
                        <option value="6">Preparando</option>
                        <option value="7">En proceso de entraga</option>
                        <option value="8">Terminado</option>
                        <option value="9">Cancelado</option>
                    </select>
                </div>
            </div>
            <br>
            @if ($listas && $listas->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>

                            </th>
                            <th>Estado</th>
                            <th>Listas a cotizar</th>
                            <th></th>
                            <th>Cotizaciones</th>
                            <th></th>
                            <th>Ordenes de venta</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listas as $lista)
                            @php
                                $cotizacion = App\Models\Cotizacion::where('lista_cotizar_id', $lista->id)->first();
                            @endphp
                            @php
                                if ($cotizacion) {
                                    $ordenVenta = App\Models\ordenVenta::where(
                                        'id_cotizacion',
                                        $cotizacion->id,
                                    )->first();
                                }

                            @endphp
                            </tr>
                            <td>
                                @if ($lista->estado == 7)
                                    <button class="btn btn-primary btn-sm" wire:click="Terminar({{ $ordenVenta->id }})"
                                        title="Cancelar cotización">
                                        Terminar
                                    </button>
                                    <button class="btn btn-danger btn-sm"
                                        wire:click="cancelarOrdenVenta({{ $ordenVenta->id }})"
                                        title="Cancelar cotización">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </button>
                                @endif
                            </td>
                            <td class="text-center columna-estatica-estado">


                                @php
                                    $estados = [
                                        1 => ['label' => 'Creando Lista', 'class' => 'badge-success'],
                                        2 => ['label' => 'Creando cotizacion', 'class' => 'badge-primary'],
                                        3 => ['label' => 'Cotizado', 'class' => 'badge-primary'],
                                        4 => ['label' => 'Esperando pago', 'class' => 'badge-warning'],
                                        5 => ['label' => 'Pagado', 'class' => 'badge-primary'],
                                        6 => ['label' => 'Preparando', 'class' => 'badge-warning'],
                                        7 => ['label' => 'En proceso de entraga', 'class' => 'badge-warning'],
                                        8 => ['label' => 'Terminado', 'class' => 'badge-success'],
                                        9 => ['label' => 'Cancelado', 'class' => 'badge-danger'],
                                    ];

                                    $estado = $estados[$lista->estado] ?? [
                                        'label' => 'Desconocido',
                                        'class' => 'badge-secondary',
                                    ];
                                @endphp

                                <span class="badge {{ $estado['class'] }}">{{ $estado['label'] }}</span>
                            </td>
                            <td class="columna-estatica-numero1 text-center align-middle">
                                {{-- {{ $lista->id }} --}}
                                {{ $lista->nombre }}
                            </td>

                            <td class="columna-estatica">

                                <button class="btn btn-info btn-sm mr-1" title="Ver PDF"
                                    wire:click="prepararPDFLista({{ $lista->id }})">
                                    <i class="fas fa-file-pdf"></i>
                                </button>

                                {{-- <button class="btn btn-info btn-sm mr-1" title="Ver PDF"
                                    onclick="window.open('{{ route('proyecto.pdf-lista', ['id' => $lista->id]) }}', '_blank')">
                                    <i class="fas fa-file-pdf"></i>
                                </button> --}}

                                @if ($lista->estado == 1)
                                    <button class="btn btn-primary btn-sm mr-1" title="Editar"
                                        wire:click="editarlista({{ $lista->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif

                                @if ($lista->estado == 1 || $lista->estado == 2)
                                    <button class="btn btn-danger btn-sm" title="Cancelar"
                                        wire:click="cancelar({{ $lista->id }})">
                                        Cancelar
                                    </button>
                                @endif

                            </td>

                            <td class="columna-estatica">

                                @if ($lista->estado == 1)
                                    <label for="">Creando lista...</label>
                                @elseif ($lista->estado == 2)
                                    <label for="">Cotizando...</label>
                                @elseif ($lista->estado >= 3 && $lista->estado != 9)
                                    <span
                                        class="fw-bold">{{ $cotizacion->nombre ?? 'No hay cotizacion recuperada' }}</span>
                                @elseif ($lista->estado == 9)
                                    <span
                                        class="fw-bold">{{ $cotizacion->nombre ?? 'No hay cotizacion recuperada' }}</span>
                                @endif
                            </td>
                            <td class="columna-estatica-columna">
                                @if ($lista->estado == 3 && $lista->estado != 9)
                                    <!-- Botón de Cancelar (existente) -->
                                    <button class="btn btn-danger btn-sm"
                                        wire:click="cancelarCotisacion({{ $cotizacion->id }})"
                                        title="Cancelar cotización">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </button>
                                @endif
                                @if ($lista->estado == 3)
                                    <button class="btn btn-primary btn-sm"
                                        wire:click="abrirModal({{ $cotizacion->id }})" title="Cancelar cotización">
                                        Aceptar
                                    </button>
                                @endif
                                @if ($lista->estado >= 3)
                                    @if ($cotizacion)
                                        <button class="btn btn-info btn-sm text-white"
                                            onclick="window.open('{{ route('proyecto.pdf-cotizacion', ['id' => $cotizacion->id]) }}', '_blank')"
                                            title="Ver el documento de la cotización">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </button>
                                    @endif
                                @endif
                            </td>
                            {{-- ////// --}}
                            <td class="">

                                @if ($lista->estado < 3)
                                    <label for="">...</label>
                                @elseif ($lista->estado == 9)
                                    <span class="fw-bold">
                                        {{ $ordenVenta->nombre ?? 'No hay orden de venta recuperada' }}</span>
                                @elseif ($lista->estado >= 4 && $lista->estado != 9)
                                    <span class="fw-bold">
                                        {{ $ordenVenta->nombre ?? 'No hay orden de venta recuperada' }}</span>
                                @endif
                            </td>
                            <td>

                                @if ($lista->estado < 4)
                                    <label for="">...</label>
                                @elseif ($cotizacion && $lista->estado > 3)
                                    <button class="btn btn-info btn-sm text-white"
                                       onclick="@if($ordenVenta) window.open('{{ route('proyecto.pdf-orden-venta', ['id' => $ordenVenta->id]) }}', '_blank'); @else alert('No hay orden de venta disponible'); @endif"
                                        title="Ver el documento de orden de venta">
                                        <i class="fas fa-file-pdf me-1"></i> PDF
                                    </button>
                                @endif
                                @if ($lista->estado == 4)
                                    <button class="btn btn-danger btn-sm"
                                        wire:click="cancelarOrdenVenta({{ $ordenVenta->id }})"
                                        title="Cancelar cotización">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </button>
                                @endif
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
    @include('livewire.ventas.recepsion-cotizacio.modalCreacionOrdenVenta')
</div>
