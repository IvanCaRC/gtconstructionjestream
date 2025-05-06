<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3" wire:poll.3000ms>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Resumen Financiero</h2>
        
        <!-- Selector de período -->
        <div class="mb-6">
            <label for="periodo" class="block text-sm font-medium text-gray-700 mb-2">Filtrar por período:</label>
            <select 
                wire:model.live="periodoSeleccionado" 
                id="periodo"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            >
                <option value="general">General (todas las fechas)</option>
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
                <p class="text-2xl font-bold text-green-600">${{ number_format($ventasTotales, 2) }}</p>
            </div>
            
            <!-- Compras Totales -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h3 class="text-blue-800 font-medium">Compras Totales</h3>
                <p class="text-2xl font-bold text-blue-600">${{ number_format($comprasTotales, 2) }}</p>
            </div>
            
            <!-- Ganancias -->
            <div class="{{ $ganancias >= 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }} p-4 rounded-lg border">
                <h3 class="{{ $ganancias >= 0 ? 'text-emerald-800' : 'text-red-800' }} font-medium">Ganancias</h3>
                <p class="text-2xl font-bold {{ $ganancias >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    ${{ number_format($ganancias, 2) }}
                </p>
            </div>
        </div>
    
        
    </div>
</div>
