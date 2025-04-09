<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Cotisaciones extends Controller
{
    
    public function index()
    {
        // Retornar la vista de la lista de proveedores
        return view('compras/cotisaciones/verCotisaciones');
    }

    public function verMisCotisaciones()
    {
        // Retornar la vista de la lista de proveedores
        return view('compras/cotisaciones/verMisCotisaciones');

    }

    public function verCarritoCotisaciones($idCotisacion)
    {
        // Retornar la vista de la lista de proveedores
        return view('compras/cotisaciones/verCarritoCotisaciones', ['idCotisacion' => $idCotisacion]);
 
    }
}
