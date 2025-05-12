<div>

    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <h3>Datos de la orden</h3>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h5>Nombre: </h5>
                        <label for="">{{ $ordenventa->nombre }}</label>
                    </div>
                    <div class="col-md-3 ">
                        <h5>Usuario que realizo</h5>
                        <label for="">{{ $ordenventa->usuario->name }}
                            {{ $ordenventa->usuario->first_last_name }}
                            {{ $ordenventa->usuario->second_last_name }}</label>

                    </div>
                    <div class="col-md-3 ">
                        <h5>Cotisacion a la que pertence</h5>
                        <label for="">
                            {{ $ordenventa->cotizacion->nombre }}
                        </label>
                    </div>

                    <div class="col-md-3 ">
                        <h5>Forma de pago</h5>
                        <label>
                            {!! $ordenventa->formaPago == 1
                                ? '<span class="badge badge-warning">Parcial</span>'
                                : ($ordenventa->formaPago == 2
                                    ? '<span class="badge badge-warning">Total</span>'
                                    : '<span class="badge badge-secondary">Estado desconocido</span>') !!}
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 ">
                        <h5>
                            Modalidad
                        </h5>
                        @switch($ordenventa->modalidad)
                            @case(1)
                                PPD
                            @break

                            @case(2)
                                PUE
                            @break

                            @default
                                Estado desconocido
                        @endswitch
                    </div>

                    <div class="col-md-3 ">
                        <h5>Estado</h5>
                        <div>
                            <label>
                                {!! $ordenventa->estado == 0
                                    ? '<span class="badge badge-danger">Por pagar</span>'
                                    : ($ordenventa->estado == 1
                                        ? '<span class="badge badge-success">Pagado</span>'
                                        : ($ordenventa->estado == 2
                                            ? '<span class="badge badge-danger">Cancelado</span>'
                                            : '<span class="badge badge-secondary">Estado desconocido</span>')) !!}
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3 ">
                        <h5>
                            Moto total
                        </h5>
                        <label style="text-align: right; font-weight: bold; color: green;">
                            {{ number_format($ordenventa->monto, 2) }} MXN
                        </label>
                    </div>
                    <div class="col-md-3 ">
                        <h5>
                            Moto por pagar
                        </h5>
                        <label style="text-align: right; font-weight: bold; color: rgb(207, 0, 0);">
                            {{ number_format($ordenventa->montoPagar, 2) }} MXN
                        </label>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <H3>Ver pagos de la orden</H3>
        <div class="card">
            <div class="card-body">
                @if (count($historialPagos) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Monto Pagado</th>
                                    <th>Comprobante</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historialPagos as $pago)
                                    <tr>
                                        <td style="text-align: right; font-weight: bold; color: green;">
                                            ${{ number_format($pago['monto'], 2) }} MXN
                                        </td>
                                        <td>


                                            @if (empty($pago['Archivo']))
                                                <span class="badge badge-secondary">Sin archivo</span>
                                            @else
                                                {{ basename($pago['Archivo']) }}
                                            @endif
                                        </td>

                                        <td>
                                            @if (empty($pago['Archivo']))
                                                <span class="badge badge-secondary">Sin archivo</span>
                                            @else
                                                <a href="{{ asset('storage/' . $pago['Archivo']) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Ver Archivo
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        No hay registros de pagos para esta orden.
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
