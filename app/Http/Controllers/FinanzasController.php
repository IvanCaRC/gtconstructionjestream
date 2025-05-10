<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanzasController extends Controller
{

    public function index()
    {
        return view('finanzas.dashboardFinanzas');
    }

    public function ingresosEgresos()
    {
        // Datos iniciales (general)
        $ventasTotales = DB::table('orden_venta')
            ->where('estado', 1)
            ->sum('monto');

        $comprasTotales = DB::table('orden_compra')
            ->where('estado', 1)
            ->sum('monto');

        $ganancias = $ventasTotales - $comprasTotales;

        return view('finanzas.ingresosEgresos.ingresosEgeresosVistaGeneral', [
            'ventasTotales' => $ventasTotales,
            'comprasTotales' => $comprasTotales,
            'ganancias' => $ganancias,
        ]);
    }
    public function filtrarDatos(Request $request)
    {
        $filtro = $request->input('filtro');
        $fechaFin = Carbon::now();
        $fechaInicio = null;

        switch ($filtro) {
            case 'ultimo_mes':
                $fechaInicio = $fechaFin->copy()->subMonth();
                break;
            case 'ultimos_3_meses':
                $fechaInicio = $fechaFin->copy()->subMonths(3);
                break;
            case 'ultimos_6_meses':
                $fechaInicio = $fechaFin->copy()->subMonths(6);
                break;
            default:
                // General: Todos los registros (sin filtro de fecha)
                $fechaInicio = $fechaFin->copy()->subMonth();
                break;
        }

        // Consultas para datos mensuales (gráfica)
        $ventasPorMes = DB::table('orden_venta')
            ->select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as año'),
                DB::raw('SUM(monto) as total')
            )
            ->where('estado', 1)
            ->when($fechaInicio, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            })
            ->groupBy('año', 'mes')
            ->orderBy('año', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        $comprasPorMes = DB::table('orden_compra')
            ->select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as año'),
                DB::raw('SUM(monto) as total')
            )
            ->where('estado', 1)
            ->when($fechaInicio, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            })
            ->groupBy('año', 'mes')
            ->orderBy('año', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        // Totales generales (para las tarjetas)
        $ventasTotales = $ventasPorMes->sum('total');
        $comprasTotales = $comprasPorMes->sum('total');
        $ganancias = $ventasTotales - $comprasTotales;

        // Formatear datos para la gráfica (meses)
        $meses = [];
        $ventas = [];
        $compras = [];

        if ($fechaInicio) {
            $currentDate = $fechaInicio->copy();
            while ($currentDate <= $fechaFin) {
                $mesNombre = $currentDate->translatedFormat('F Y'); // Ej: "Enero 2024"
                $meses[] = $mesNombre;

                $ventaMes = $ventasPorMes->first(function ($item) use ($currentDate) {
                    return $item->mes == $currentDate->month && $item->año == $currentDate->year;
                });
                $ventas[] = $ventaMes ? round($ventaMes->total, 2) : 0;

                $compraMes = $comprasPorMes->first(function ($item) use ($currentDate) {
                    return $item->mes == $currentDate->month && $item->año == $currentDate->year;
                });
                $compras[] = $compraMes ? round($compraMes->total, 2) : 0;

                $currentDate->addMonth();
            }
        } else {
            // Caso "General": Mostrar todos los meses disponibles
            foreach ($ventasPorMes as $venta) {
                $fecha = Carbon::createFromDate($venta->año, $venta->mes, 1);
                $meses[] = $fecha->translatedFormat('F Y');
                $ventas[] = round($venta->total, 2);
            }
            foreach ($comprasPorMes as $compra) {
                $compras[] = round($compra->total, 2);
            }
        }

        return response()->json([
            'meses' => $meses,
            'ventas' => $ventas,
            'compras' => $compras,
            'ventasTotales' => round($ventasTotales, 2),
            'comprasTotales' => round($comprasTotales, 2),
            'ganancias' => round($ganancias, 2),
        ]);
    }
    public function ordenesVenta()
    {
        // Retornar la vista de la lista de proveedores
        return view('finanzas.ordenesVenta.vistaOrdenVentaFin');
    }

    public function ordenescompra()
    {
        // Retornar la vista de la lista de proveedores
        return view('finanzas.ordenCompra.vistaOrdenCompraFin');
    }
}
