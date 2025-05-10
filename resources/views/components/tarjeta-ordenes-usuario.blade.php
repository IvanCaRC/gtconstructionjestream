<div>
<div class="container mt-4">
    <div class="row">
        <!-- Tarjeta 1: Cantidad de clientes -->
        <div class="col-md-3">
            <div class="card border-left-primary shadow text-center p-3">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">Cantidad de clientes</div>
                    <div class="h4 font-weight-bold text-gray-800">{{ $clientesCount }}</div>
                    <i class="fas fa-users fa-2x text-primary mt-2"></i>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Cantidad de proyectos -->
        <div class="col-md-3">
            <div class="card border-left-success shadow text-center p-3">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-2">Cantidad de proyectos</div>
                    <div class="h4 font-weight-bold text-gray-800">{{ $proyectosCount }}</div>
                    <i class="fas fa-project-diagram fa-2x text-success mt-2"></i>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3: Órdenes de venta culminadas -->
        <div class="col-md-3">
            <div class="card border-left-info shadow text-center p-3">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-2">Órdenes de venta</div>
                    <div class="h4 font-weight-bold text-gray-800">{{ $ordenesCulminadasCount }}</div>
                    <i class="fas fa-clipboard-check fa-2x text-info mt-2"></i>
                </div>
            </div>
        </div>

        <!-- Tarjeta 4: Suma total de ventas -->
        <div class="col-md-3">
            <div class="card border-left-warning shadow text-center p-3">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-2">Suma total de ventas</div>
                    <div class="h4 font-weight-bold text-gray-800">{{ $montoTotalCulminadas }}</div>
                    <i class="fas fa-dollar-sign fa-2x text-warning mt-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="container my-5">
        <div  class="row py-10 ">

            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Estado de Ganancias</h6>
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
                        <h6 class="m-0 font-weight-bold text-primary">Estado de Proyectos</h6>
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
                                <i class="fas fa-circle text-primary"></i> En proceso
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Cancelados
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> Concretados
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

</div>