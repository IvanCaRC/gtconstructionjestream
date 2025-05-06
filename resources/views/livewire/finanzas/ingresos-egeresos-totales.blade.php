<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3" wire:poll.3000ms>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Resumen Financiero</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
    
        <!-- Botón para recalcular -->
    </div>
</div>
