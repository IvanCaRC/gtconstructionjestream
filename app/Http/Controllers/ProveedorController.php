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
        // Validar los datos de las direcciones
        // $request->validate([
        //     'direcciones' => 'required|json',
        // ]);

        // Decodificar las direcciones desde JSON
        $direcciones = json_decode($request->input('direcciones'), true);

        // Obtener el proveedor con id = 1
        $proveedor = Proveedor::find(1);

        if (!$proveedor) {
            return redirect()->back()->with('error', 'No se encontró el proveedor con id = 1.');
        }

        // Iniciar una transacción de base de datos
        DB::beginTransaction();

        try {
            // Guardar cada dirección asociada al proveedor
            foreach ($direcciones as $direccionData) {
                $direccion = new Direccion([
                    'cp' => $direccionData['address']['cp'],
                    'estado' => $direccionData['address']['estado'],
                    'ciudad' => $direccionData['address']['ciudad'],
                    'municipio' => $direccionData['address']['municipio'],
                    'colonia' => $direccionData['address']['colonia'],
                    'calle' => $direccionData['address']['calle'],
                    'numero' => $direccionData['address']['numero'],
                    'referencia' => $direccionData['address']['referencia'],
                    'Latitud' => $direccionData['latlng']['lat'] ?? null,
                    'Longitud' => $direccionData['latlng']['lng'] ?? null,
                    'proveedor_id' => $proveedor->id, // Asociar al proveedor con id = 1
                ]);

                $direccion->save();
            }

            // Commit de la transacción
            DB::commit();

            return redirect()->route('compras.proveedores.viewProveedores')->with('success', 'Direcciones guardadas exitosamente.');
        } catch (\Exception $e) {
            // Rollback en caso de error
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al guardar las direcciones: ' . $e->getMessage());
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
