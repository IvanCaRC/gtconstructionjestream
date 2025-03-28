<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FichasTecnicas extends Controller
{
    //
    public function index()
    {
        // Retornar la vista de la lista de proveedores
        return view('ventas.fichasTecnicas.fichasTecnicas');
    }

    public function viewEspecItem($idItem)
    {
        // Retornar la vista de la lista de proveedores
        return view('ventas.fichasTecnicas.fichaEspecificaItem', ['idItem' => $idItem]);
    }
    
}
