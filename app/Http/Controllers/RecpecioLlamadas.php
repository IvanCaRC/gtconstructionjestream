<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Direccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecpecioLlamadas extends Controller
{
    //
    public function index()
    {
        // Retornar la vista de la lista de proveedores
        return view('ventas.cliente.recepcionLlamadas');
    }

    public function vista()
    {
        // Retornar la vista de la lista de proveedores
        return view('ventas.cliente.gestionClientes');
    }

    public function store(Request $request)
    {
        // Validar los datos de las direcciones y el ID del proveedor

        // Log para verificar los valores recibidos


        // Decodificar las direcciones desde JSON
        $direcciones = json_decode($request->input('direcciones'), true);
        print_r($direcciones);

        // Obtener el proveedor con el ID recibido del formulario
        $cliente = Cliente::find($request->input('cliente_id'));

        // Iniciar una transacción de base de datos
        DB::beginTransaction();

        try {
            // Guardar cada dirección asociada al proveedor
            foreach ($direcciones as $direccionData) {
                $direccion = new Direccion([
                    'pais' => $direccionData['address']['pais'],
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
                    'cliente_id' => $cliente->id, // Asociar al proveedor recién creado
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
}
