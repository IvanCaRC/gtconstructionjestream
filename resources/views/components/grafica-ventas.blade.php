<div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container mt-4">
        <div class="row">
            <!-- Tarjeta 1: Cantidad de clientes -->
            <div class="col-md-3">
                <div class="card border-left-primary shadow text-center p-3">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">Cotizaciones Hechas</div>
                        <div class="h4 font-weight-bold text-gray-800">{{$cotisacionesCount}}</div>
                        <i class="fas fa-users fa-2x text-primary mt-2"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Cantidad de proyectos -->
            <div class="col-md-3">
                <div class="card border-left-success shadow text-center p-3">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-2">Cotizaciones Activas
                        </div>
                        <div class="h4 font-weight-bold text-gray-800">{{$cotisacionesActivasCount}}</div>
                        <i class="fas fa-project-diagram fa-2x text-success mt-2"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Órdenes de venta culminadas -->
            <div class="col-md-3">
                <div class="card border-left-info shadow text-center p-3">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-2">Órdenes de compra</div>
                        <div class="h4 font-weight-bold text-gray-800">{{$ordenesCulminadasCount}}</div>
                        <i class="fas fa-clipboard-check fa-2x text-info mt-2"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 4: Suma total de ventas -->
            <div class="col-md-3">
                <div class="card border-left-warning shadow text-center p-3">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-2">Suma total de compras
                        </div>
                        <div class="h4 font-weight-bold text-gray-800">{{$montoTotalCulminadas}}</div>
                        <i class="fas fa-dollar-sign fa-2x text-warning mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row py-10 ">

            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Estado de Gastos de Compras</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area" style="height: 300px;">
                            <canvas id="myPieChart2"></canvas>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Estado de Proyectos</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="myPieChart3"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('myPieChart2').getContext('2d');

            const ventasLabels = {!! json_encode(array_keys($ventasPorMes)) !!};
            const ventasData = {!! json_encode(array_values($ventasPorMes)) !!};

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ventasLabels,
                    datasets: [{
                        label: 'Ventas Mensuales ($)',
                        data: ventasData,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        });
    </script>

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctxPie = document.getElementById('myPieChart3').getContext('2d');

        const estados = {!! json_encode($estadoCotisaciones) !!};

        const labels = {
            0: 'Activa',
            1: 'Enviada',
            2: 'Aceptada pendiente de pago',
            3: 'Pagado',
            4: 'Comprando',
            5: 'En proceso de entrega',
            6: 'Terminado',
            7: 'Cancelado',
        };

        const colors = {
            0: '#4e73df',
            1: '#36b9cc',
            2: '#1cc88a',
            3: '#f6c23e',
            4: '#858796',
            5: '#fd7e14',
            6: '#20c997',
            7: '#28a745',
        };

        const estadosLabels = Object.keys(estados).map(key => labels[key] ?? 'Desconocido');
        const estadosData = Object.values(estados);
        const estadosColors = Object.keys(estados).map(key => colors[key] ?? '#6c757d');

        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: estadosLabels,
                datasets: [{
                    data: estadosData,
                    backgroundColor: estadosColors,
                    hoverBackgroundColor: estadosColors,
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '60%',
            }
        });
    });
</script>
@endpush
@stack('scripts')

</div>
