<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentasController extends Controller
{

    public function index(Request $request)
    {
        $filtroTiempo = $request->input('filtro_tiempo', 'todos');

        return view('ventas.dashboardVentas', [

            'filtroTiempo' => $filtroTiempo,
        ]);
    }
}
