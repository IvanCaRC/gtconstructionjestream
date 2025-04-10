<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemCotizar extends Controller
{
    public function index()
    {
        // Retornar la vista de la lista de proveedores
        return view('compras.catalogoCotisacion.catalogoItems');
    }

    public function vistaEspecificaDeCotisacion($idItem)
    {
        // Retornar la vista de la lista de proveedores
        return view('compras.catalogoCotisacion.catalogoItems', ['idItem' => $idItem]);

    }

} 
