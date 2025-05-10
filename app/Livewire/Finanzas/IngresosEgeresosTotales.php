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
    //Variables para graficar
    public $ingresos = [];
    public $egresos = [];
    public $categorias = [];


    public function mount()
    {
        $this->calcularTotales();
        $this->cargarDatosGraficos();
    }

    public function cargarDatosGraficos()
    {
        $fechaFiltro = $this->obtenerFechasFiltro();

        // Recuperar ingresos (ventas individuales)
        $queryVentas = ordenVenta::where('estado', 1);
        if ($fechaFiltro) {
            $queryVentas->where('created_at', '>=', $fechaFiltro);
        }
        $ventas = $queryVentas->select('id', 'monto')->get();
        $this->ingresos = $ventas->pluck('monto')->toArray();
        $this->categorias = $ventas->pluck('id')->toArray();

        // Recuperar egresos (compras individuales)
        $queryCompras = ordenCompra::where('estado', 1);
        if ($fechaFiltro) {
            $queryCompras->where('created_at', '>=', $fechaFiltro);
        }
        $compras = $queryCompras->select('id', 'monto')->get();
        $this->egresos = $compras->pluck('monto')->toArray();
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

        return match ($this->periodoSeleccionado) {
            'ultimo_mes' => $hoy->subMonth(),
            'ultimos_3_meses' => $hoy->subMonths(3),
            'ultimos_6_meses' => $hoy->subMonths(6),
            default => null, // General (sin filtro)
        };
    }

    public function updatedPeriodoSeleccionado()
{
    $this->calcularTotales();
    $this->cargarDatosGraficos();
    $this->dispatch('refreshChart', [
        'ingresos' => $this->ingresos,
        'egresos' => $this->egresos,
        'categorias' => $this->categorias
    ]);
}

    public function render()
{
    return view('livewire.finanzas.ingresos-egeresos-totales', [
        'ingresos' => $this->ingresos,
        'egresos' => $this->egresos,
        'categorias' => $this->categorias
    ]);
}
}
