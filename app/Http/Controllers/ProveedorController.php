<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        // Retornar la vista de la lista de proveedores
        return view('compras.proveedores.viewProveedores');
    }
}
