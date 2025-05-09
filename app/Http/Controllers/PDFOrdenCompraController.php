<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Direccion;
use App\Models\Item;
use App\Models\ItemEspecifico;
use Barryvdh\DomPDF\Facade\Pdf;


class PDFOrdenCompraController extends Controller
{
    public function generarOrdenCompraPDF($cotizacionId, $proveedorId)
    {
        $cotizacion = Cotizacion::findOrFail($cotizacionId);
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
        $itemsProveedor = collect(json_decode($cotizacion->items_cotizar_proveedor, true))
            ->where('proveedor_id', $proveedorId)
            ->map(function ($item) {
                // Buscar el ItemEspecifico usando el id del ítem en la lista
                $itemEspecifico = ItemEspecifico::where('id', $item['id'])->first();

                // Recuperar el nombre y descripción desde la tabla Item usando item_id
                $itemBase = $itemEspecifico
                    ? Item::where('id', $itemEspecifico->item_id)->first()
                    : null;

                // Asignar los valores con respaldo si no existen
                $item['nombre'] = $itemBase->nombre ?? 'Nombre no disponible';
                $item['descripcion'] = $itemBase->descripcion ?? 'Sin descripción';
                $item['unidad'] = $itemEspecifico->unidad ?? 'Unidad no especificada';

                return $item;
            })
            ->values();

        if ($itemsProveedor->isEmpty()) {
            return back()->with('error', 'No hay ítems registrados para este proveedor.');
        }

        $proveedorNombre = $itemsProveedor->first()['nombreProveedor'] ?? 'Proveedor desconocido';
        $total = $itemsProveedor->sum(fn($item) => $item['precio'] * $item['cantidad']);

        // Datos dinámicos para la vista PDF
        $data = [
            'proveedorNombre' => $proveedorNombre,
            'clienteNombre' => $clienteNombre,
            'clienteCorreo' => $clienteCorreo,
            'clienteRFC' => $clienteRFC,
            'clienteContacto' => $clienteContacto,
            'direccionEntrega' => $direccionEntrega,
            'items' => $itemsProveedor->toArray(),
            'total' => $total,
        ];

        $pdf = Pdf::loadView('pdf.ordencompra', $data)->setPaper('a4', 'portrait');

        return $pdf->stream("OrdenCompra_Proveedor_{$proveedorId}.pdf");
    }
}
