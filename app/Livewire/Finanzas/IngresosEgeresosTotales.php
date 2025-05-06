<?php

namespace App\Livewire\Finanzas;

use Livewire\Component;
use App\Models\ordenVenta;
use App\Models\ordenCompra;

class IngresosEgeresosTotales extends Component
{
    public $ventasTotales = 0;
    public $comprasTotales = 0;
    public $ganancias = 0;

    public function mount()
    {
        $this->calcularTotales();
    }

    public function calcularTotales()
    {
        // Calcular ventas totales (ordenes de venta con estado = 1)
        $this->ventasTotales = ordenVenta::where('estado', 1)
            ->sum('monto');

        // Calcular compras totales (ordenes de compra con estado = 1)
        $this->comprasTotales = ordenCompra::where('estado', 1)
            ->sum('monto');

        // Calcular ganancias
        $this->ganancias = $this->ventasTotales - $this->comprasTotales;
    }

    public function render()
    {
        return view('livewire.finanzas.ingresos-egeresos-totales');
    }
}