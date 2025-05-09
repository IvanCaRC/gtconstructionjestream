<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Direccion;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ordenCompra;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;


class PDFOrdenCompraController extends Controller
{
    
    public function generarOrdenCompraPDF($ordenComraId)
    {
        
        $ordencompra = ordenCompra::findOrFail($ordenComraId);
        $provedorActual = Proveedor::findOrFail($ordencompra->id_provedor);
        $cotizacion = Cotizacion::findOrFail($ordencompra->id_cotizacion);
        $proyecto = $cotizacion->proyecto; // Obtener el proyecto desde la cotización
        $direccion = Direccion::find($proyecto->direccion_id);

        // Formatear la dirección eliminando los valores que contengan "Campo no recuperado"
        $componentesDireccion = [
            $direccion->calle,
            $direccion->numero,
            $direccion->colonia,
            $direccion->municipio,
            $direccion->estado,
            $direccion->pais,
            "CP: {$direccion->cp}"
        ];

        // Filtrar cualquier campo con el texto "Campo no recuperado"
        $componentesDireccion = array_filter($componentesDireccion, function ($valor) {
            return $valor !== "Campo no recuperado";
        });

        // Convertir la dirección en un string limpio
        $direccionEntrega = !empty($componentesDireccion) ? implode(', ', $componentesDireccion) : "Dirección no registrada";

        $cliente = $proyecto->cliente; // Obtener el cliente desde el proyecto
        $clienteNombre = $cliente->nombre ?? 'Cliente no registrado';
        $clienteCorreo = $cliente->correo ?? 'Correo no registrado';
        $clienteRFC = $cliente->rfc ?? 'RFC no registrado';

        // Extraer datos del contacto (teléfono)
        $telefonos = json_decode($cliente->telefono, true);
        $clienteContacto = isset($telefonos[0]) ? $telefonos[0]['nombre'] . ' - ' . $telefonos[0]['numero'] : 'Contacto no disponible';



        // Filtrar los ítems por proveedor
        // $itemsProveedor = collect(json_decode($ordencompra->items_cotizar_proveedor, true))
        //     ->where('proveedor_id', $proveedorId)
        //     ->map(function ($item) {
        //         // Buscar el ItemEspecifico usando el id del ítem en la lista
        //         $itemEspecifico = ItemEspecifico::where('id', $item['id'])->first();

        //         // Recuperar el nombre y descripción desde la tabla Item usando item_id
        //         $itemBase = $itemEspecifico
        //             ? Item::where('id', $itemEspecifico->item_id)->first()
        //             : null;

        //         // Asignar los valores con respaldo si no existen
        //         $item['nombre'] = $itemBase->nombre ?? 'Nombre no disponible';
        //         $item['descripcion'] = $itemBase->descripcion ?? 'Sin descripción';
        //         $item['unidad'] = $itemEspecifico->unidad ?? 'Unidad no especificada';

        //         return $item;
        //     })
        //     ->values();


        // $proveedorNombre = $itemsProveedor->first()['nombreProveedor'] ?? 'Proveedor desconocido';
        // $total = $itemsProveedor->sum(fn($item) => $item['precio'] * $item['cantidad']);

        $items_proveedor = json_decode($ordencompra->items_cotizar_proveedor ?? '[]', true);
        foreach ($items_proveedor as $item) {
            $item_especifico = ItemEspecifico::find($item['id']); // 🔹 Primero buscamos el ItemEspecifico
            $item_base = $item_especifico ? Item::find($item_especifico->item_id) : null; // 🔹 Luego recuperamos el Item asociado

            $items_cotizacion[] = [
                'cantidad' => $item['cantidad'] ?? '-',
                'nombre' => $item['nombreDeItem'] ?? $item_base?->nombre ?? 'Nombre no disponible',
                'unidad'=>  $item['unidad'] ?? 'Unidad no especificada',
                'descripcion' => $item_base?->descripcion ?? 'Descripción no disponible',
                'marca' => $item_especifico?->marca ?? 'Sin marca', // 🔹 Ahora agregamos la marca correctamente
                'precio' => $item['precio'] ?? $item_base?->precio ?? 0,
            ];
        }
        // Datos dinámicos para la vista PDF


        $data = [
            'proveedorNombre' => $provedorActual->nombre,
            'clienteNombre' => $clienteNombre,
            'clienteCorreo' => $clienteCorreo,
            'clienteRFC' => $clienteRFC,
            'clienteContacto' => $clienteContacto,
            'direccionEntrega' => $direccionEntrega,
            'items_cotizacion' => $items_cotizacion,
            'total' => $ordencompra->monto,
        ];

        $pdf = Pdf::loadView('pdf.ordencompra', $data)->setPaper('a4', 'portrait');

        return $pdf->stream("ordencompra.pdf");
    }
}
