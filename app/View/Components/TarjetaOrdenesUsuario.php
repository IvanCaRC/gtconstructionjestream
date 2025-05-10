<?php

namespace App\View\Components;


use App\Models\Proyecto;
use App\Models\Cliente;
use App\Models\ordenVenta;
use Carbon\Carbon;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

class TarjetaOrdenesUsuario extends Component
    {
        public $iduser;
    public $clientesCount;
    public $ordenesCount;
    public $proyectosCount;
    public $ordenesCulminadasCount;
    public $montoTotalCulminadas;
    public $ventasPorMes = [];
    public $estadosProyectos;

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

        // Ordenes del usuario
        $ordenesQuery = Cliente::where('user_id', $iduser);
        if ($fechaInicio) {
            $ordenesQuery->where('created_at', '>=', $fechaInicio);
        }
        $this->clientesCount = $ordenesQuery->count();

        // IDs de los clientes asociados al usuario
        $clientesIds = Cliente::where('user_id', $iduser)->pluck('id');

        // Proyectos de esos clientes
        $proyectosQuery = Proyecto::whereIn('cliente_id', $clientesIds);
        if ($fechaInicio) {
            $proyectosQuery->where('created_at', '>=', $fechaInicio);
        }
        $this->proyectosCount = $proyectosQuery->count();

        // Ordenes totales
        $ordenesQuery = ordenVenta::where('id_usuario', $iduser);
        if ($fechaInicio) {
            $ordenesQuery->where('created_at', '>=', $fechaInicio);
        }
        $this->ordenesCount = $ordenesQuery->count();

        // Ordenes culminadas (estado = 1)
        $ordenesCulminadasQuery = ordenVenta::where('id_usuario', $iduser)
            ->where('estado', 1);
        if ($fechaInicio) {
            $ordenesCulminadasQuery->where('created_at', '>=', $fechaInicio);
        }
        $this->ordenesCulminadasCount = $ordenesCulminadasQuery->count();
        $this->montoTotalCulminadas = $ordenesCulminadasQuery->sum('monto');

        $proyectos = $proyectosQuery->get();

        // Contar por estado
        $this->estadosProyectos = $proyectos->groupBy('proceso')->map->count();

        $ventasQuery = ordenVenta::select(
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

    public function render()
    {
        return view('components.tarjeta-ordenes-usuario');
    }
}
