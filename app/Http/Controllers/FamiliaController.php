<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
