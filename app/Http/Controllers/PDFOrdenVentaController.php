<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ordenVenta;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFOrdenVentaController extends Controller
{
    public function generarPDFOrdenVenta($id)
    {
        try {
            $ordenVenta = ordenVenta::with('cotizacion', 'cliente', 'usuario', 'direccion')->findOrFail($id);
            $cotizacion = $ordenVenta->cotizacion;
            $cliente = $ordenVenta->cliente->nombre ?? 'Nombre no disponible';

            // ✅ Recuperación y limpieza de la dirección en `generarPDFOrdenVenta`
            $direccion = optional(Direccion::find($cotizacion->proyecto->direccion_id));

            $componentesDireccion = [
                $direccion?->calle,
                $direccion?->numero,
                $direccion?->colonia,
                $direccion?->municipio,
                $direccion?->estado,
                $direccion?->pais,
                $direccion?->cp ? "CP: {$direccion->cp}" : ''
            ];

            // ✅ Filtrar cualquier campo que contenga "Campo no recuperado" o que esté vacío
            $componentesDireccion = array_filter($componentesDireccion, fn($valor) => $valor !== "Campo no recuperado" && trim($valor) !== '');

            // ✅ Convertimos en una dirección limpia o mostramos "Dirección no registrada"
            $direccion = !empty($componentesDireccion) ? implode(', ', $componentesDireccion) : "Dirección no registrada";

            // ✅ Información de la orden de venta
            $numeroOrden = $ordenVenta->id;
            $fechaEmision = $ordenVenta->created_at->format('d/m/Y') ?? 'Sin fecha';
            $formaPago = $ordenVenta->formaPago ?? 'No especificado';
            $metodoPago = $ordenVenta->metodoPago ?? 'No especificado';
            $estado = $ordenVenta->estado == 1 ? 'Confirmada' : 'Pendiente';

            // ✅ Recuperamos los ítems desde la cotización
            $items_stock = json_decode($cotizacion->items_cotizar_stock ?? '[]', true);
            $items_proveedor = json_decode($cotizacion->items_cotizar_proveedor ?? '[]', true);

            // ✅ Unificamos los ítems correctamente
            $items_orden = [];
            foreach (array_merge($items_stock, $items_proveedor) as $item) {
                $item_especifico = ItemEspecifico::find($item['id']);
                $item_base = $item_especifico ? Item::find($item_especifico->item_id) : null;

                $precio_unitario = $item['precio'] ?? $item_base?->precio ?? 0;

                $items_orden[] = [
                    'cantidad' => $item['cantidad'] ?? '-',
                    'nombre' => $item['nombreDeItem'] ?? $item_base?->nombre ?? 'Nombre no disponible',
                    'descripcion' => $item_base?->descripcion ?? 'Descripción no disponible',
                    'marca' => $item_especifico?->marca ?? 'Sin marca',
                    'precio_unitario' => $precio_unitario,
                    'precio' => $precio_unitario * ($item['cantidad'] ?? 1),
                ];
            }

            // ✅ Calculamos costos totales
            $subtotal = collect($items_orden)->sum(fn($item) => $item['precio']);
            $impuestos = $subtotal * 0.16;
            $total = $subtotal + $impuestos;

            // ✅ Pasamos los datos a la vista del PDF
            $data = [
                'title' => 'Orden Venta',
                'numeroOrden' => $ordenVenta->id, // ✅ Aquí aseguramos que el ID se pase correctamente
                'fechaEmision' => $fechaEmision,
                'cliente' => $cliente,
                'direccion' => $direccion,
                'formaPago' => $formaPago,
                'metodoPago' => $metodoPago,
                'estado' => $estado,
                'items_orden' => $items_orden,
                'subtotal' => $subtotal,
                'impuestos' => $impuestos,
                'total' => ceil(($total) * 10) / 10
            ];

            // ✅ Depuración previa para validar `$data` antes de cargar la vista
            if (empty($data)) {
                abort(500, 'Los datos están vacíos. Verifica la recuperación.');
            }

            $pdf = Pdf::loadView('pdf.ordenventa', $data)->setPaper('a4', 'portrait');
            return $pdf->stream('orden_venta.pdf');
        } catch (\Exception $e) {
            abort(500, 'Error al generar el PDF. ' . $e->getMessage());
        }
    }
}
