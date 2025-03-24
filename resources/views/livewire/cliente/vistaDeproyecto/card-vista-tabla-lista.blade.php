<div>
    <h3 class="ml-3">Listas del proyecto</h3>
    <div class="card">
        <div class="card-body">
            <div class="text-left mb-3">
                <button class="btn btn-custom" style="background-color: #4c72de; color: white;"
                    wire:click="saveListaNueva">Agregar proyecto</button>
            </div>
            <div class="row m   b-3">
                <div class="col-md-10">
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar proyecto...">
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
                            <th>Cotisaciones</th>
                            <th>Ordenes de venta</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listas as $lista)
                            </tr>
                            <td>
                                {!! $lista->estado == 1
                                    ? '<span class="badge badge-success">Activo</span>'
                                    : ($lista->estado == 2
                                        ? '<span class="badge badge-secondary">Inactivo</span>'
                                        : ($lista->estado == 3
                                            ? '<span class="badge badge-warning">Cotizando</span>'
                                            : ($lista->estado == 4
                                                ? '<span class="badge badge-primary">Cotizando</span>'
                                                : ($lista->estado == 5
                                                    ? '<span class="badge badge-danger">Cancelado</span>'
                                                    : '<span class="badge badge-secondary">Estado desconocido</span>')))) !!}
                                {{ $lista->nombre }}
                                @if ($lista->estado == 1)
                                    <button class="btn btn-info btn-custom">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-primary btn-custom"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-danger">
                                        Desactivar
                                    </button>
                                @elseif ($lista->estado == 2)
                                    <button class="btn btn-info btn-custom">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-primary btn-custom"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-success">
                                        Activar
                                    </button>
                                @else
                                    <button class="btn btn-info btn-custom">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-primary btn-custom"><i class="fas fa-edit"></i></button>
                                @endif
                            </td>
                            <td>
                                @if ($lista->estado == 1)
                                    <button class="btn btn-custom" style="background-color: #4c72de; color: white;">
                                        Mandar a cotizar
                                    </button>
                                @elseif ($lista->estado == 2)
                                    <label for="">Preguntar si tiene una cotisacion vinculada, si es asi deve
                                        poder verse, si no es asi debe decir que se necesita activar para mandar a
                                        cotisacion</label>
                                @elseif ($lista->estado == 3)
                                    <label for="">Cotizando...</label>
                                @elseif ($lista->estado == 4)
                                    <label for="">Mostrar cotisacion y opciones</label>
                                @elseif ($lista->estado == 5)
                                    <label for="">Preguntar si tiene una acotisacion y mostratla cancelada. si
                                        no la tiene simplemente poner cancelado</label>
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
                    No hay proyectos registrados para este cliente.
                </div>
            @endif
        </div>
    </div>
</div>
