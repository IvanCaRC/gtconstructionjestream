<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        return view('compras.proveedores.viewProveedores');
    }

    public function crearProveedor()
    {
        return view('compras.proveedores.createProveedores');
    }
}
