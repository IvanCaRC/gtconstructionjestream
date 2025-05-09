

<div class="container">
    @if($rol === 'Compras')
        <div class="d-flex flex-wrap justify-content-between">
            <!-- Tarjeta 1: Ventas Mensuales -->
            <div class="card border-left-primary shadow mb-3 flex-grow-1 mx-2" style="min-width: 250px;">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas (Mensuales)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($datos['ventas_mensuales'], 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Ventas Anuales -->
            <div class="card border-left-success shadow mb-3 flex-grow-1 mx-2" style="min-width: 250px;">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ventas (Anuales)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($datos['ventas_anuales'], 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Proyectos Completados -->
            <div class="card border-left-info shadow mb-3 flex-grow-1 mx-2" style="min-width: 250px;">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Órdenes Completadas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $datos['proyectos'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 4: Pendientes -->
            <div class="card border-left-warning shadow mb-3 flex-grow-1 mx-2" style="min-width: 250px;">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Órdenes Pendientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $datos['pendientes'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            No hay métricas disponibles para tu rol.
        </div>
    @endif
</div>
