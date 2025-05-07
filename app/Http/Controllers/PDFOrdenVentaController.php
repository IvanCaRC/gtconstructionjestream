<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use App\Models\OrdenVenta;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFOrdenVentaController extends Controller
{
    public function generarPDFOrdenVenta($id)
    {
        try {
            $ordenVenta = OrdenVenta::with('cotizacion', 'cliente', 'usuario', 'direccion')->findOrFail($id);
            $cotizacion = $ordenVenta->cotizacion;
            $cliente = $ordenVenta->cliente->nombre ?? 'Nombre no disponible';

            // ✅ Formateamos la dirección correctamente
            $direccion = $ordenVenta->direccion
                ? implode(', ', array_filter([
                    $ordenVenta->direccion->calle ?? '',
                    $ordenVenta->direccion->numero ?? '',
                    $ordenVenta->direccion->colonia ?? '',
                    "CP: {$ordenVenta->direccion->cp}" ?? ''
                ]))
                : 'Sin dirección asignada';

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
                'numeroOrden' => $numeroOrden,
                'fechaEmision' => $fechaEmision,
                'cliente' => $cliente,
                'direccion' => $direccion,
                'formaPago' => $formaPago,
                'metodoPago' => $metodoPago,
                'estado' => $estado,
                'items_orden' => $items_orden,
                'subtotal' => $subtotal,
                'impuestos' => $impuestos,
                'total' => $total
            ];

            // ✅ Depuración previa para validar `$data` antes de cargar la vista
            if (empty($data)) {
                abort(500, 'Los datos están vacíos. Verifica la recuperación.');
            }

            $pdf = Pdf::loadView('pdf.OrdenVenta', $data)->setPaper('a4', 'portrait');
            return $pdf->stream('orden_venta.pdf');
        } catch (\Exception $e) {
            abort(500, 'Error al generar el PDF. ' . $e->getMessage());
        }
    }
}
