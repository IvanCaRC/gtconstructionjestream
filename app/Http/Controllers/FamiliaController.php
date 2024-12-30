<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FamiliaController extends Controller
{
    public function index()
    {
        // Lógica para mostrar los usuarios
        return view('compras.familias.viewFamilias');
    }

    public function crearUsuario()
    {
        return view('compras.familias.createFamilias');
    }
}
