<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Direccion;
use App\Models\Item;
use App\Models\ListasCotizar;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFCotizacionController extends Controller
{
    public function generarPDFCotizacion($id)
    {
        try {
            // ✅ Recuperar cotización con relaciones necesarias
            $cotizacion = Cotizacion::with('usuario', 'proyecto', 'listaCotizar')->findOrFail($id);
            $cliente = Cliente::findOrFail($cotizacion->proyecto->cliente_id);
            $direccion = Direccion::where('cliente_id', $cliente->id)->first();
            $lista = ListasCotizar::where('proyecto_id', $cotizacion->proyecto->id)
                ->where('id', $cotizacion->lista_cotizar_id)
                ->first();

            if (!$lista) {
                abort(404, 'Lista no encontrada.');
            }

            // ✅ Usuario que atendió la solicitud (nombre + apellidos)
            $usuario_atendio = "{$cotizacion->usuario->name} {$cotizacion->usuario->first_last_name} {$cotizacion->usuario->second_last_name}";

            // ✅ Recuperación de dirección
            $componentes_direccion = array_filter([
                $direccion->calle ?? '',
                $direccion->numero ?? '',
                $direccion->colonia ?? '',
                $direccion->municipio ?? '',
                $direccion->ciudad ?? '',
                $direccion->estado ?? '',
                $direccion->pais ?? '',
                "CP: {$direccion->cp}" ?? ''
            ]);

            // ✅ Recuperación del tipo de proyecto
            $tipo_proyecto = $cotizacion->proyecto->tipo == 1 ? 'Suministro' : 'Obra';

            $items_stock = json_decode($cotizacion->items_cotizar_stock ?? '[]', true);
            $items_proveedor = json_decode($cotizacion->items_cotizar_proveedor ?? '[]', true);

            // ✅ Unificamos los ítems en una sola estructura
            $items_cotizacion = [];

            // ✅ Procesamos los ítems de stock
            foreach ($items_stock as $item) {
                $items_cotizacion[] = [
                    'cantidad' => $item['cantidad'] ?? '-',
                    'nombre' => $item['nombre'] ?? 'Nombre no disponible',
                    'descripcion' => $item['descripcion'] ?? 'Descripción no disponible',
                    'precio' => 0, // ✅ Este campo se llenará si existe en proveedor
                ];
            }

            // ✅ Procesamos los ítems de proveedor y aseguramos que los datos queden registrados
            foreach ($items_proveedor as $item) {
                $item_base = Item::find($item['id']); // ✅ Buscamos el ítem en `Item`
                $items_cotizacion[] = [
                    'cantidad' => $item['cantidad'] ?? '-',
                    'nombre' => $item['nombreDeItem'] ?? 'Nombre no disponible',
                    'descripcion' => $item_base?->descripcion ?? 'Descripción no disponible', // ✅ Se toma de la BD si está disponible
                    'precio' => $item['precio'] ?? 0, // ✅ Recuperamos el precio correctamente
                ];
            }

            $total_cotizacion = 0; // ✅ Inicializamos el total

            foreach ($items_cotizacion as $item) {
                $total_cotizacion += ($item['precio'] ?? 0) * ($item['cantidad'] ?? 0); // ✅ Precio unitario * cantidad
            }

            // ✅ Datos organizados para el PDF
            $data = [
                'title' => 'Cotización',
                'cotizacion' => $cotizacion,
                'usuario_atendio' => $usuario_atendio,
                'cliente' => $cliente->nombre,
                'direccion' => implode(', ', $componentes_direccion),
                'proyecto' => $cotizacion->proyecto->nombre,
                'tipo_proyecto' => $tipo_proyecto,
                'items_cotizacion' => $items_cotizacion, // ✅ Ahora apuntamos a la lista fusionada
                'subtotal' => $total_cotizacion, // ✅ Subtotal sin impuestos
                'impuestos' => $total_cotizacion * 0.16, // ✅ Asumiendo un 16% de IVA, ajusta si es necesario
                'total' => $total_cotizacion + ($total_cotizacion * 0.16), // ✅ Total con impuestos
            ];

            // ✅ Generar el PDF con los datos reales
            $pdf = Pdf::loadView('pdf.cotizacion', $data)->setPaper('a4', 'portrait');

            return $pdf->stream('cotizacion.pdf');
        } catch (\Exception $e) {
            abort(500, 'Error al generar el PDF.');
        }
    }
}
