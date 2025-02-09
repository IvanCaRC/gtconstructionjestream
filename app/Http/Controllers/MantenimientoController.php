<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function index()
    {
        // Retornar la vista index
        return view('mantenimiento.enconstruccion');
    }
}
