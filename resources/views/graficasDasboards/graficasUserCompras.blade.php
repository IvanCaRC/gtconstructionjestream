<div>

    <div class="container">
        <div class="d-flex flex-wrap justify-content-between">
            <!-- Tarjeta 1: Ventas Mensuales -->
            <div class="card border-left-primary shadow mb-3 flex-grow-1 mx-2" style="min-width: 250px;">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas (Mensuales)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">13213123123</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">3213123213</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">3123123123</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">3213123</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row py-10">
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
