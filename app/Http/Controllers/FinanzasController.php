<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanzasController extends Controller
{
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

    public function ingresosEgresos()
    {
        // Retornar la vista de la lista de proveedores
        return view('finanzas.ingresosEgresos.ingresosEgeresosVistaGeneral');

    }
}
