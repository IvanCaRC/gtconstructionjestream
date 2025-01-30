<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    DB::transaction(function () use ($request) {
        // Crear el proveedor
        $proveedor = Proveedor::create($request->except('direcciones'));

        // Guardar direcciones si existen
        if ($request->has('direcciones')) {
            $direcciones = json_decode($request->direcciones, true);
            
            foreach ($direcciones as $dir) {
                Direccion::create([
                    'cp' => $dir['address']['cp'] ?? null,
                    'estado' => $dir['address']['estado'] ?? null,
                    'ciudad' => $dir['address']['ciudad'] ?? null,
                    'municipio' => $dir['address']['municipio'] ?? null,
                    'colonia' => $dir['address']['colonia'] ?? null,
                    'calle' => $dir['address']['calle'] ?? null,
                    'numero' => $dir['address']['numero'] ?? null,
                    'referencia' => $dir['address']['referencia'] ?? null,
                    'Latitud' => $dir['latlng']['lat'] ?? null,
                    'Longitud' => $dir['latlng']['lng'] ?? null,
                    'proveedor_id' => $proveedor->id
                ]);
            }
        }
    });

    return redirect()->route('compras.proveedores.index');
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
