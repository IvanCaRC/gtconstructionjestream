<?php

namespace App\Http\Controllers;

use App\Models\ordenVenta;
use App\Models\ordenCompra;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
class adminController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $view = $this->getRoleView($user);

        $filtroMeses = $request->input('filtro', 6);
        $fechaInicio = Carbon::now()->subMonths($filtroMeses)->startOfMonth();

        $totales = $this->calcularTotales($filtroMeses);

        // Proyectos activos en el mes actual
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $proyectosActivos = Proyecto::where('estado', 1)
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->count();

        // Gráfica de procesos (filtrados por fecha)
        $proyectos = Proyecto::select('proceso', DB::raw('count(*) as total'))
            ->where('fecha', '>=', $fechaInicio)
            ->groupBy('proceso')
            ->get();

        $labels = [
            0 => 'Creando lista',
            1 => 'Creando cotización',
            2 => 'Cotizado',
            3 => 'Esperando pago',
            4 => 'Pagado',
            5 => 'Preparando',
            6 => 'En entrega',
            7 => 'Venta terminada',
            8 => 'Cancelado',
        ];

        $procesoLabels = [];
        $procesoTotales = [];
        foreach ($proyectos as $p) {
            $procesoLabels[] = $labels[$p->proceso] ?? 'Desconocido';
            $procesoTotales[] = $p->total;
        }

        // Gráfica de estados (filtrados por fecha)
        $estados = Proyecto::select('estado', DB::raw('count(*) as total'))
            ->where('fecha', '>=', $fechaInicio)
            ->groupBy('estado')
            ->get();

        $estadoLabels = [
            1 => 'Activo',
            2 => 'Inactivo',
            3 => 'Cancelado',
        ];

        $estadoChartLabels = [];
        $estadoChartTotales = [];
        foreach ($estados as $e) {
            $estadoChartLabels[] = $estadoLabels[$e->estado] ?? 'Desconocido';
            $estadoChartTotales[] = $e->total;
        }

        // Gráfica de proyectos por mes (filtrados por rango)


        $proyectosPorMes = Proyecto::select(
                DB::raw("DATE_FORMAT(fecha, '%Y-%m') as mes_anio"),
                DB::raw("DATE_FORMAT(fecha, '%M %Y') as mes_nombre"),
                DB::raw("COUNT(*) as total")
            )
            ->where('fecha', '>=', $fechaInicio)
            ->groupBy('mes_anio', 'mes_nombre')
            ->orderBy('mes_anio')
            ->get()
            ->keyBy('mes_anio');
        
        // Generar los últimos X meses (formato: Y-m) y armar labels y datos
        $meses = collect();
        $totalesMeses = collect();
        
        for ($i = $filtroMeses - 1; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $mesKey = $mes->format('Y-m');
            $mesNombre = ucfirst($mes->translatedFormat('F Y'));
        
            $meses->push($mesNombre);
            $totalesMeses->push($proyectosPorMes[$mesKey]->total ?? 0);
        }
        

        return view($view, [
            'ventas' => $totales['ventas'],
            'compras' => $totales['compras'],
            'ganancias' => $totales['ganancias'],
            'filtroMeses' => $filtroMeses,
            'proyectosActivos' => $proyectosActivos,
            'procesoLabels' => $procesoLabels,
            'procesoTotales' => $procesoTotales,
            'estadoChartLabels' => $estadoChartLabels,
            'estadoChartTotales' => $estadoChartTotales,
            'meses' => $meses,
            'totalesMeses' => $totalesMeses,
        ]);
    }

    private function calcularTotales($meses)
    {
        $fechaInicio = Carbon::now()->subMonths($meses)->startOfMonth();

        $ventasTotales = ordenVenta::where('estado', 1)
            ->where('created_at', '>=', $fechaInicio)
            ->sum('monto');

        $comprasTotales = ordenCompra::where('estado', 1)
            ->where('created_at', '>=', $fechaInicio)
            ->sum('monto');

        return [
            'ventas' => $ventasTotales,
            'compras' => $comprasTotales,
            'ganancias' => $ventasTotales - $comprasTotales
        ];
    }

    private function getRoleView($user)
    {
        $role = $user->roles()->first()->name;

        return match ($role) {
            'Administrador' => 'admin.dashboardAdmin',
            'Ventas' => 'ventas.dashboardVentas',
            'Compras' => 'compras.dashboardCompras',
            'Finanzas' => 'finanzas.dashboardFinanzas',
            default => 'home',
        };
    }
}
