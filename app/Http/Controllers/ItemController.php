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

    public function crearItem()
    {
        // Retornar la vista de la lista de proveedores
        return view('compras.items.createItems');
    }

    public function editItem($idItem)
    {
        return view('compras.items.edicionItem', ['idItem' => $idItem]);
    }
    

    public function ciewEspecItem($idItem)
    {
        return view('compras.items.vistaEspecificaItem', ['idItem' => $idItem]);
    }
}
