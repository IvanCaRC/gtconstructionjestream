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

        .columna-estatica-numero1 {
            width: 140px;
            /* o el valor que tú quieras */

        }

        .columna-estatica-estado {
            width: 90px;
            /* o el valor que tú quieras */

        }
    </style>
    <h3 class="ml-3">Ordenes de compra del proyecto</h3>
    <div class="card">
        <div class="card-body">
            <div class="row m   b-3">
                <div class="col-md-10">
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar lista..."
                        wire:model='searchTerm' wire:keydown='search'>
                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2" wire:model="statusFiltro" wire:change="search">
                        <option value="">Estado</option>
                        <option value="0">Por pagar</option>
                        <option value="1">Pagado</option>
                        <option value="2">Cancelado</option>
                    </select>
                </div>
            </div>
            <br>
            @if ($listas && $listas->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Modalidad</th>
                            <th>Forma de pago</th>
                            <th>Monto</th>
                            <th>Monto por pagar</th>
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listas as $lista)
                            </tr>
                            <td>
                                {{ $lista->created_at }}
                            </td>
                            <td>
                                {!! $lista->estado == 0
                                    ? '<span class="badge badge-danger">Por pagar</span>'
                                    : ($lista->estado == 1
                                        ? '<span class="badge badge-success">Pagado</span>'
                                        : ($lista->estado == 2
                                            ? '<span class="badge badge-danger">Cancelado</span>'
                                            : '<span class="badge badge-secondary">Estado desconocido</span>')) !!}
                            </td>
                            <td>
                                @switch($lista->modalidad)
                                    @case(1)
                                        PPD
                                    @break

                                    @case(2)
                                        PUE
                                    @break

                                    @default
                                        Estado desconocido
                                @endswitch

                            </td>
                            <td>
                                {!! $lista->estado == 0
                                    ? '<span class="badge badge-primary">Deposito</span>'
                                    : ($lista->estado == 1
                                        ? '<span class="badge badge-primary">Efectivo</span>'
                                        : ($lista->estado == 2
                                            ? '<span class="badge badge-primary">Transferencia</span>'
                                            : ($lista->estado == 3
                                                ? '<span class="badge badge-danger">Cancelada</span>'
                                                : '<span class="badge badge-secondary">Estado desconocido</span>'))) !!}
                            </td>
                            <td style="text-align: left; font-weight: bold; color: green;">
                                {{ number_format($lista->monto, 2) }} MXN
                            </td>
                            <td style="text-align: left; font-weight: bold; color: rgb(207, 0, 0);">
                                {{ number_format($lista->montoPagar, 2) }} MXN
                            </td>
                            <td>
                                {{ $lista->nombre }}
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm text-white"
                                    onclick="window.open('{{ route('proyecto.pdf-orden-compra', ['idOrdencompra' => $lista->id]) }}', '_blank')"
                                    title="Generar y ver el documento de la orden de compra">
                                    <i class="fas fa-file-pdf me-1"></i> PDF
                                </button>
                                <button class="btn btn-info btn-sm" wire:click="viewOrden({{ $lista->id }})"
                                    title="Ver proyecto">
                                    <i class="fas fa-eye"></i>
                                </button>

                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div>
                    No hay ordenes en esta lista.
                </div>
            @endif
        </div>
    </div>
</div>
