<?php

namespace App\Http\Controllers;

use App\Models\ItemEspecifico;
use Illuminate\Http\Request;

class FichasTecnicas extends Controller
{
    //
    public function index()
    {
        // Retornar la vista de la lista de proveedores
        return view('ventas.fichasTecnicas.fichasTecnicas');
    }

    public function viewEspecIte2m($idItem)
    {
        // Retornar la vista de la lista de proveedores
        return view('ventas.fichasTecnicas.fichaEspecificaItem', ['idItem' => $idItem]);
    }

    public function viewEspecItem($idItem)
    {
        // Buscar el item en la base de datos
        $itemEspecifico = ItemEspecifico::findOrFail($idItem);

        // Retornar la vista con el item específico
        return view('ventas.fichasTecnicas.fichaEspecificaItem', compact('itemEspecifico'));
    }
    
}
