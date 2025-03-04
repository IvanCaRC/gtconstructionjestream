<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecpecioLlamadas extends Controller
{
    //
    public function index()
    {
        // Retornar la vista de la lista de proveedores
        return view('ventas.cliente.recepcionLlamadas');
    }

    public function vista()
    {
        // Retornar la vista de la lista de proveedores
        return view('ventas.cliente.gestionClientes');
    }
}
