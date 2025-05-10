<?php

namespace App\View\Components;

use App\Models\Cotizacion;
use App\Models\ordenCompra;
use Closure;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class GraficaVentas extends Component
{
    /**
     * Create a new component instance.
     */
    public $iduser;
    public $cotisacionesCount;
    public $cotisacionesActivasCount;
    public $ordenesCulminadasCount;
    public $montoTotalCulminadas;
    public $ventasPorMes;
    public $estadoCotisaciones;

    public function __construct($iduser, $filtroTiempo = 'todos')
    {
        $this->iduser = $iduser;

        // Filtro base
        // Filtro de fecha
        $fechaInicio = match ($filtroTiempo) {
            '1m' => Carbon::now()->subMonth(),
            '3m' => Carbon::now()->subMonths(3),
            '6m' => Carbon::now()->subMonths(6),
            default => null,
        };

        $cotisacionesQuery = Cotizacion::where('id_usuario_compras', $iduser);
        if ($fechaInicio) {
            $cotisacionesQuery->where('created_at', '>=', $fechaInicio);
        }
        $this->cotisacionesCount = $cotisacionesQuery->count();

        $this->cotisacionesActivasCount = Cotizacion::where('id_usuario_compras', $iduser)
            ->when($fechaInicio, function ($query) use ($fechaInicio) {
                return $query->where('created_at', '>=', $fechaInicio);
            })
            ->where('estado', '!=', 7)
            ->count();

        $ordenesCulminadasQuery = ordenCompra::where('id_usuario', $iduser)
            ->where('estado', 1);
        if ($fechaInicio) {
            $ordenesCulminadasQuery->where('created_at', '>=', $fechaInicio);
        }
        $this->ordenesCulminadasCount = $ordenesCulminadasQuery->count();
        $this->montoTotalCulminadas = $ordenesCulminadasQuery->sum('monto');

        $cotisaciones = $cotisacionesQuery->get();

        // Contar por estado
        $this->estadoCotisaciones = $cotisaciones->groupBy('estado')->map->count();

        $ventasQuery = ordenCompra::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"),
            DB::raw("SUM(monto) as total")
        )
            ->where('id_usuario', $iduser)
            ->where('estado', 1)
            ->groupBy('mes')
            ->orderBy('mes');

        if ($fechaInicio) {
            $ventasQuery->where('created_at', '>=', $fechaInicio);
        }

        $this->ventasPorMes = $ventasQuery->pluck('total', 'mes')->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.grafica-ventas');
    }
}
