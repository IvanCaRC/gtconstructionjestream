<?php

namespace App\Livewire\Finanzas;

use Livewire\Component;
use App\Models\ordenVenta;
use App\Models\ordenCompra;
use Carbon\Carbon;

class IngresosEgeresosTotales extends Component
{
    public $ventasTotales = 0;
    public $comprasTotales = 0;
    public $ganancias = 0;
    public $periodoSeleccionado = 'general';

    public function mount()
    {
        $this->calcularTotales();
    }

    public function calcularTotales()
    {
        $fechaFiltro = $this->obtenerFechasFiltro();

        // Calcular ventas totales
        $queryVentas = ordenVenta::where('estado', 1);
        if ($fechaFiltro) {
            $queryVentas->where('created_at', '>=', $fechaFiltro);
        }
        $this->ventasTotales = $queryVentas->sum('monto');

        // Calcular compras totales
        $queryCompras = ordenCompra::where('estado', 1);
        if ($fechaFiltro) {
            $queryCompras->where('created_at', '>=', $fechaFiltro);
        }
        $this->comprasTotales = $queryCompras->sum('monto');

        // Calcular ganancias
        $this->ganancias = $this->ventasTotales - $this->comprasTotales;
    }

    protected function obtenerFechasFiltro()
    {
        $hoy = Carbon::now();

        return match($this->periodoSeleccionado) {
            'ultimo_mes' => $hoy->subMonth(),
            'ultimos_3_meses' => $hoy->subMonths(3),
            'ultimos_6_meses' => $hoy->subMonths(6),
            default => null, // General (sin filtro)
        };
    }

    public function updatedPeriodoSeleccionado()
    {
        $this->calcularTotales();
    }

    public function render()
    {
        return view('livewire.finanzas.ingresos-egeresos-totales');
    }
}