<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class graficasUserVentas extends Controller
{
    //
    public function verGraficas($idUser)
    {
        // Retornar la vista de la lista de proveedores
        return view('graficasDasboards.graficasUserVentas', ['idUser' => $idUser]);
    }
}
