<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Detalle Proveedor</h2>
                    </div>
                    <div class="card-body d-flex">
                        <div class="pl-3">
                            <a href="#" class="d-block mb-3"
                                wire:click="editProveedor({{ $proveedor->id }})">Editar Proveedor</a>
                            <h5 class="card-title mt-4 role-description">Nombre: {{ $proveedor->nombre }}</h5>
                            <h5 class="card-title mt-3">Descripción: {{ $proveedor->descripcion ?? '' }}</h5>
                            <h5 class="card-title mt-3">Correo: {{ $proveedor->correo ?? '' }}</h5>
                            <h5 class="card-title mt-3">RFC: {{ $proveedor->rfc ?? '' }}</h5>
                            <h5 class="card-title mt-3">Teléfonos:</h5>
                            @foreach ($telefonos as $index => $telefono)
                                <div class="input-group mb-2">
                                    <label for="">{{ 'Teléfono: ' . $telefono }}</label>
                                </div>
                            @endforeach
                            <h5 class="card-title mt-3">Familias:</h5>
                            @foreach ($familiasSeleccionadas as $index => $familia)
                                <div class="w-100 d-flex align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        {{ $familia['nombre'] }}
                                    </div>
                                </div>
                            @endforeach
                            <h5 class="card-title mt-3">Direciones:</h5>
                            @if ($proveedor->direcciones && $proveedor->direcciones->count() > 0)
                                @foreach ($proveedor->direcciones as $direccion)
                                    {{ $direccion->estado }}, {{ $direccion->ciudad }},
                                    {{ $direccion->calle }}, {{ $direccion->numero }}<br>
                                @endforeach
                            @else
                                N/A
                            @endif
                            <h5 class="card-title mt-3">Estado:
                                @if ($proveedor->estado)
                                    <span class="badge badge-success">Actualizado</span>
                                @else
                                    <span class="badge badge-danger">Desactualizado</span>
                                @endif
                            </h5>
                            <h5 class="card-title mt-3">Archivo de Facturación PDF:</h5>
                            @if ($proveedor->archivo_facturacion_pdf)
                                <a href="{{ asset('storage/' . $proveedor->archivo_facturacion_pdf) }}" target="_blank"
                                    class="btn btn-secondary">
                                    Ver Archivo de Facturación
                                </a>
                            @else
                                <p>No hay archivo de facturación disponible.</p>
                            @endif

                            <h5 class="card-title mt-3">Datos bancarios:</h5>
                            @if ($proveedor->datos_bancarios_pdf)
                                <a href="{{ asset('storage/' . $proveedor->datos_bancarios_pdf) }}" target="_blank"
                                    class="btn btn-secondary">
                                    Ver Archivo de Datos Bancarios
                                </a>
                            @else
                                <p>No hay archivo bancario disponible.</p>
                            @endif

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="#" class="text-danger"
                            onclick="confirmDeletion({{ $proveedor->id }}, '{{ $proveedor->nombre }}')">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row py-10">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Total de compra</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Creditos de compras</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="myPieChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Creditos
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Compras
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        function confirmDeletion(proveedorId, proveedorNombre) {
            Swal.fire({
                title: `¿Estás seguro de que deseas eliminar a ${proveedorNombre}?`,
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    ejecutarEliminacionProveedor(proveedorId, proveedorNombre);
                } else {
                    Swal.fire('Cancelado', 'La eliminación ha sido cancelada.', 'info');
                }
            });
        }

        function ejecutarEliminacionProveedor(proveedorId, proveedorNombre) {
            @this.call('verificarAsignacionProvedor', proveedorId).then((asignada) => {
                if (asignada) {
                    Swal.fire(
                        'No se puede eliminar',
                        'Este proveedor está asignada a un item, no se puede eliminar.',
                        'error'
                    );
                } else {
                    @this.call('eliminar', proveedorId).then(() => {
                        Swal.fire(
                            'Eliminado!',
                            `${proveedorNombre} ha sido eliminado.`,
                            'success'
                        );
                    });
                }
            });
        }
    </script>
</div>
