@extends('layouts.app')

@section('title', 'Dashboard Administracion')
@section('activeCategorias', 'active')

@section('contend')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            height: 400px;
            margin: auto;
        }
    </style>

    <br>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">¡Bienvenido de vuelta! </h1>
            <form method="GET" action="{{ route('admin.dashboardAdmin') }}" class="mb-4">
                <select name="filtro" onchange="this.form.submit()" class="form-control custom-select-width d-inline-block">
                    <option value="1" {{ $filtroMeses == 1 ? 'selected' : '' }}>Este mes</option>
                    <option value="3" {{ $filtroMeses == 3 ? 'selected' : '' }}>Últimos 3 meses</option>
                    <option value="6" {{ $filtroMeses == 6 ? 'selected' : '' }}>Últimos 6 meses</option>
                </select>
            </form>

            <style>
                .custom-select-width {
                    width: 300px;
                    /* Puedes ajustar el valor aquí */
                }
            </style>
        </div>


        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Ventas totales</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($ventas, 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Compras totales</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($compras, 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Utilidades</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($ganancias, 2) }}
                                </div>

                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Proyectos Activos (Este Mes)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $proyectosActivos }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-project-diagram fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="container-fluid">

            <div class="row">

                <div class="col-xl-6 col-lg-6 col-md-6 mb-4">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div
                            class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                            <h6 class="m-0 font-weight-bold text-dark">Proyectos por Mes</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-bar" style="height: 300px;">
                                <canvas id="proyectosPorMesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-3 mb-4">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="m-0 font-weight-bold text-dark">Proceso de Proyectos</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="chart-pie pt-3 pb-2" style="height: 240px;">
                                <canvas id="estadoProyectosChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-3 mb-4">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="m-0 font-weight-bold text-dark">Estado de Proyectos</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="chart-pie pt-3 pb-2" style="height: 240px;">
                                <canvas id="estadoProyectosEstadoChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <script>
                const ctxProyectosMes = document.getElementById('proyectosPorMesChart').getContext('2d');

                const proyectosPorMesChart = new Chart(ctxProyectosMes, {
                    type: 'bar',
                    data: {
                        labels: @json($meses),
                        datasets: [{
                            label: 'Proyectos',
                            data: @json($totalesMeses),
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            </script>



            <script>
                const estadoChart = document.getElementById('estadoProyectosEstadoChart').getContext('2d');
                new Chart(estadoChart, {
                    type: 'doughnut',
                    data: {
                        labels: @json($estadoChartLabels),
                        datasets: [{
                            data: @json($estadoChartTotales),
                            backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                            hoverOffset: 8,
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        cutout: '60%'
                    }
                });
            </script>


            <script>
                const ctx = document.getElementById('estadoProyectosChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($procesoLabels),
                        datasets: [{
                            data: @json($procesoTotales),
                            backgroundColor: [
                                '#4e73df', '#1cc88a', '#f6c23e', '#36b9cc',
                                '#e74a3b', '#858796', '#fd7e14', '#20c997', '#6f42c1'
                            ],
                            hoverOffset: 8,
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        cutout: '60%' // Donut style
                    }
                });
            </script>




        </div>
    </div>
@endsection
