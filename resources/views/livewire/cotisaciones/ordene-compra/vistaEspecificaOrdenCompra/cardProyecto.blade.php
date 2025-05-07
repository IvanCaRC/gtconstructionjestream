<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h5>Nombre:  {{$cotisacion->nombre}}</h5>
                <label> {{$cotisacion->nombre}}</label>
            </div>
            <div class="col-md-4 ">
                <h5>Usuario que realizo</h5>
                <div>
                    <label>{{ $cotisacion->usuarioCompras->name}} {{ $cotisacion->usuarioCompras->first_last_name}} {{ $cotisacion->usuarioCompras->second_last_name}} </label>
                </div>
            </div>
            <div class="col-md-4 ">
                <h5>Estado</h5>
                <div>
                    <label>
                        {!! $cotisacion->estado == 0
                            ? '<span class="badge badge-primary">Activa</span>'
                            : ($cotisacion->estado == 1
                                ? '<span class="badge badge-primary">Enviada</span>'
                                : ($cotisacion->estado == 2
                                    ? '<span class="badge badge-warning">Aceptada pendiente de pago</span>'
                                    : ($cotisacion->estado == 3
                                        ? '<span class="badge badge-primary">Pagado</span>'
                                        : ($cotisacion->estado == 4
                                            ? '<span class="badge badge-warning">Comprando</span>'
                                            : ($cotisacion->estado == 5
                                                ? '<span class="badge badge-warning">En proceso de entrega</span>'
                                                : ($cotisacion->estado == 6
                                                    ? '<span class="badge badge-success">Terminado</span>'
                                                    : ($cotisacion->estado == 7
                                                    ? '<span class="badge badge-primary">Cancelado</span>'
                                                    : '<span class="badge badge-secondary">Estado desconocido</span>'))))))) !!}
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
