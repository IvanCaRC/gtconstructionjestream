@extends('layouts.app')
@section('title', 'Dashboard Finanzas')
@section('activeFinanzas', 'active')

@section('contend')
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script><div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">¡Estadisticas del area de finanzas!</h2>
    <div class="bg-white p-6 rounded-lg shadow-md">


        <!-- Selector de período -->
        <div class="mb-6">
            <label for="periodo" class="block text-sm font-medium text-gray-700 mb-2">Filtrar por período:</label>
            <select id="periodo" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
             
                <option value="ultimo_mes">Último mes</option>
                <option value="ultimos_3_meses">Últimos 3 meses</option>
                <option value="ultimos_6_meses">Últimos 6 meses</option>
            </select>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Ventas Totales -->
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h3 class="text-green-800 font-medium">Ventas Totales</h3>
                <p class="text-2xl font-bold text-green-600">$<span id="ventasTotales">{{ number_format($ventasTotales, 2) }}</span></p>
            </div>

            <!-- Compras Totales -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h3 class="text-blue-800 font-medium">Compras Totales</h3>
                <p class="text-2xl font-bold text-blue-600">$<span id="comprasTotales">{{ number_format($comprasTotales, 2) }}</span></p>
            </div>

            <!-- Ganancias -->
            <div id="gananciasContainer" class="{{ $ganancias >= 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }} p-4 rounded-lg border">
                <h3 id="gananciasLabel" class="{{ $ganancias >= 0 ? 'text-emerald-800' : 'text-red-800' }} font-medium">Ganancias</h3>
                <p id="ganancias" class="text-2xl font-bold {{ $ganancias >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    $<span>{{ number_format($ganancias, 2) }}</span>
                </p>
            </div>
        </div>

        <!-- Gráfica -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Gráfica de Ingresos y Egresos</h2>
            <div id="chart"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    let chart;

    // Inicializar gráfica vacía
    function initChart() {
        const options = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: true }
            },
            series: [],
            xaxis: { categories: [] },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                }
            },
            colors: ['#4CAF50', '#F44336'], // Verde para ventas, rojo para compras
        };

        chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    }

    // Actualizar tarjetas y gráfica
    function updateUI(response) {
        // Actualizar tarjetas
        $('#ventasTotales').text(response.ventasTotales.toLocaleString('es-MX', { minimumFractionDigits: 2 }));
        $('#comprasTotales').text(response.comprasTotales.toLocaleString('es-MX', { minimumFractionDigits: 2 }));
        $('#ganancias span').text(response.ganancias.toLocaleString('es-MX', { minimumFractionDigits: 2 }));

        // Actualizar colores de ganancias
        const gananciasContainer = $('#gananciasContainer');
        const gananciasLabel = $('#gananciasLabel');
        const gananciasText = $('#ganancias');

        if (response.ganancias >= 0) {
            gananciasContainer.removeClass('bg-red-50 border-red-200').addClass('bg-emerald-50 border-emerald-200');
            gananciasLabel.removeClass('text-red-800').addClass('text-emerald-800');
            gananciasText.removeClass('text-red-600').addClass('text-emerald-600');
        } else {
            gananciasContainer.removeClass('bg-emerald-50 border-emerald-200').addClass('bg-red-50 border-red-200');
            gananciasLabel.removeClass('text-emerald-800').addClass('text-red-800');
            gananciasText.removeClass('text-emerald-600').addClass('text-red-600');
        }

        // Actualizar gráfica
        chart.updateOptions({
            xaxis: { categories: response.meses },
            series: [
                { name: 'Ventas', data: response.ventas },
                { name: 'Compras', data: response.compras }
            ]
        });
    }

    // Cargar datos iniciales (general)
    function loadInitialData() {
        $.ajax({
            url: "{{ route('finanzas.filtrar') }}",
            type: "GET",
            data: { filtro: 'general' },
            success: function(response) {
                updateUI(response);
            },
            error: function(xhr) {
                console.error("Error al cargar datos iniciales");
            }
        });
    }

    // Inicializar
    initChart();
    loadInitialData();

    // Manejar cambio de filtro
    $('#periodo').change(function() {
        const filtro = $(this).val();
        $.ajax({
            url: "{{ route('finanzas.filtrar') }}",
            type: "GET",
            data: { filtro: filtro },
            success: function(response) {
                updateUI(response);
            },
            error: function(xhr) {
                console.error("Error al filtrar datos");
            }
        });
    });
});
</script>
@endsection