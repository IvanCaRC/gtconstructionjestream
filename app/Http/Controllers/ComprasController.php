<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Illuminate\Http\Request;

class ComprasController extends Controller
{

    public function index()
    {
        return view('compras.dashboardCompras');
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
