<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
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
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'email' => 'required|email|unique:proveedores,email',
            'rfc' => 'required|string|max:13|unique:proveedores,rfc',
            'telefono' => 'nullable|array',
            'telefono.*' => 'nullable|string|max:20',
        ]);

        $proveedor = Proveedor::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'email' => $request->email,
            'rfc' => $request->rfc,
        ]);

        // Guardar teléfonos si existen
        if ($request->has('telefono')) {
            foreach ($request->telefono as $tel) {
                if (!empty($tel)) {
                    $proveedor->telefonos()->create(['numero' => $tel]);
                }
            }
        }
    }


    public function verProveedor($idproveedor)
    {
        return view('compras.proveedores.viewProveedorEspecifico', ['idproveedor' => $idproveedor]);
    }

    public function editProveedor($idproveedor)
    {
        return view('compras.proveedores.editProveedores', ['idproveedor' => $idproveedor]);
    }
}
