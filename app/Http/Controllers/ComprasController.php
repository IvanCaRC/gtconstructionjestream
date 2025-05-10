<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Illuminate\Http\Request;

class ComprasController extends Controller
{

    public function index(Request $request)
    {
        $filtroTiempo = $request->input('filtro_tiempo', 'todos');
        return view('compras.dashboardCompras', [

            'filtroTiempo' => $filtroTiempo,
        ]);
    }

    public function ordenesCompra()
    {
        // Retornar la vista de la lista de proveedores
        return view('compras.cotisaciones.verOrdenesCompra');
    }

    public function vistaEspecificaOrdenCompra($idCotisacion)
    {

        $cotisacion = Cotizacion::findOrFail($idCotisacion);
        // Retornar la vista de la lista de proveedores
        return view('compras/cotisaciones/vistaEspecificaOrdenesCompra', compact('cotisacion'));
    }
}
