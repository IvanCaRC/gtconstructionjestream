<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentasRecepcionCotisaciones extends Controller
{
    //
    public function index()
    {
        // Retornar la vista de la lista de proveedores
        return view('ventas.recepcionCotizaciones.recepcionCotizacion');
    }
}
