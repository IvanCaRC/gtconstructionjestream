<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // Retornar la vista de la lista de proveedores
        return view('compras.items.viewItems');
    }
}
