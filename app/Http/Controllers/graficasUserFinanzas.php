<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class graficasUserFinanzas extends Controller
{
    //
    public function verGraficas($idUser)
    {
        // Retornar la vista de la lista de proveedores
        return view('graficasDasboards.graficasUserFinanzas', ['idUser' => $idUser]);
    }
}
