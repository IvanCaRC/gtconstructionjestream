<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Direccion;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Session;

class PDFListaController extends Controller
{
    public function generarPDFLista($id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $data = [
            'title' => 'Lista de Ítems a Cotizar',
            'proyecto' => Session::get('proyecto_nombre', 'Nombre no disponible'),
            'proyecto_fecha' => Session::get('proyecto_fecha', 'Fecha no registrada'),
            'proyecto_tipo' => Session::get('proyecto_tipo', 'No registrado'),
            'proyecto_direccion' => Session::get('proyecto_direccion', 'No registrado'),
            'frentes' => Session::get('frentes', 'No especificados'),
            'fondos' => Session::get('fondos', 'No especificados'),
            'alturasTecho' => Session::get('alturasTecho', 'No especificadas'),
            'areasTotales' => Session::get('areasTotales', 'No especificadas'),
            'alturasMuros' => Session::get('alturasMuros', 'No especificadas'),
            'canalones' => Session::get('canalones', 'No especificados'),
            'perimetrales' => Session::get('perimetrales', 'No especificados'),
            'caballetes' => Session::get('caballetes', 'No especificados'),
            'estructuras' => explode(', ', Session::get('estructuras', '')),
            'cantidades' => explode(', ', Session::get('cantidades', '')),
            'estructura_cantidad' => json_decode(Session::get('estructura_cantidad', '[]'), true),
            'usuario' => Session::get('usuario', 'Usuario desconocido'),
            'usuario_first_last_name' => Session::get('usuario_first_last_name', ''),
            'usuario_second_last_name' => Session::get('usuario_second_last_name', ''),
            'cliente_nombre' => Session::get('cliente_nombre', 'No disponible'),
            'cliente_correo' => Session::get('cliente_correo', 'No disponible'),
            'cliente_contacto_1' => Session::get('cliente_contacto_1', 'No registrado'),
            'cliente_telefono_1' => Session::get('cliente_telefono_1', 'No registrado'),
            'cliente_contacto_2' => Session::get('cliente_contacto_2', 'No registrado'),
            'cliente_telefono_2' => Session::get('cliente_telefono_2', 'No registrado'),
            'items_cotizar' => Session::get('items_cotizar', 'No hay ítems registrados'),
            'items_cotizar_data' => Session::get('items_cotizar_data', []),
            'items_cotizar_temporales' => Session::get('items_cotizar_temporales', 'No hay ítems temporales'),
            'items_cotizar_temporales_data' => Session::get('items_cotizar_temporales_data', []),
        ];

        $pdf = Pdf::loadView('pdf.lista', $data)->setPaper('a4', 'portrait');

        return $pdf->stream('lista_items.pdf');
    }

    public function generarPDFListaCompras($lista_id)
    {
        // Buscar la lista de cotización con el ID recibido
        $lista = ListasCotizar::findOrFail($lista_id);

        // Obtener el proyecto relacionado con la lista
        $proyecto = Proyecto::findOrFail($lista->proyecto_id);

        // Obtener el cliente asociado al proyecto
        $cliente = Cliente::findOrFail($proyecto->cliente_id);

        // Obtener la dirección que se encuentra asociada al proyecto
        $direccion = optional(Direccion::find($proyecto->direccion_id));

        // Obtener números de teléfono
        $telefonos = json_decode($cliente->telefono ?? '[]', true);
        if (!empty($telefonos) && isset($telefonos[0])) {
            $nombre_contacto_1 = $telefonos[0]['nombre'] ?? 'No registrado';
            $numero_1 = $telefonos[0]['numero'] ?? 'No registrado';
        } else {
            $nombre_contacto_1 = 'No registrado';
            $numero_1 = 'No registrado';
        }

        if (!empty($telefonos) && isset($telefonos[1])) {
            $nombre_contacto_2 = $telefonos[1]['nombre'] ?? 'No registrado';
            $numero_2 = $telefonos[1]['numero'] ?? 'No registrado';
        } else {
            $nombre_contacto_2 = 'No registrado';
            $numero_2 = 'No registrado';
        }

        // Recuperar los ítems de la lista a cotizar
        $items = json_decode($lista->items_cotizar ?? '[]', true);
        $items_data = [];
        foreach ($items as $item) {
            $item_especifico = ItemEspecifico::find($item['id']);
            $item_base = $item_especifico ? Item::find($item_especifico->item_id) : null;
            $imagen = $item_especifico ? asset('storage/' . explode(',', $item_especifico->image)[0]) : '';

            $items_data[] = [
                'imagen' => $imagen,
                'nombre' => $item_base?->nombre ?? 'Nombre no disponible',
                'marca' => $item_especifico?->marca ?? 'Marca no registrada',
                'descripcion' => $item_base?->descripcion ?? 'Sin descripción',
                'cantidad' => $item['cantidad'] . ' ' . ($item_especifico?->unidad ?? 'Unidad no especificada'),
            ];
        }

        // Dirección limpia y formateada
        $componentes_direccion = array_filter([
            $direccion->calle ?? '',
            $direccion->numero ?? '',
            $direccion->colonia ?? '',
            $direccion->municipio ?? '',
            $direccion->ciudad ?? '',
            $direccion->estado ?? '',
            $direccion->pais ?? '',
            $direccion->cp ? "CP: {$direccion->cp}" : ''
        ], fn($valor) => $valor !== 'Campo no recuperado' && trim($valor) !== '');

        $direccion_depurada = !empty($componentes_direccion) ? implode(', ', $componentes_direccion) : 'Dirección no registrada';

        // Recuperar medidas del proyecto
        $datos_medidas = json_decode($proyecto->datos_medidas ?? '[]', true);
        $frentes = array_column($datos_medidas, 'frente', 'No especificado');
        $fondos = array_column($datos_medidas, 'fondo', 'No especificado');
        $alturasTecho = array_column($datos_medidas, 'alturaTecho', 'No especificado');
        $areasTotales = array_column($datos_medidas, 'areaTotal', 'No especificado');
        $alturasMuros = array_column($datos_medidas, 'alturaMuros', 'No especificado');
        $canalones = array_column($datos_medidas, 'canalon', 'No especificado');
        $perimetrales = array_column($datos_medidas, 'perimetral', 'No especificado');
        $caballetes = array_column($datos_medidas, 'caballete', 'No especificado');

        // Recuperar datos adicionales
        $datos_adicionales = json_decode($proyecto->datos_adicionales ?? '[]', true);
        $estructuras = array_column($datos_adicionales, 'estructura', 'No especificado');
        $cantidades = array_column($datos_adicionales, 'cantidad', 'No definida');
        $estructura_cantidad = array_map(fn($estructura, $cantidad) => "$estructura - $cantidad", $estructuras, $cantidades);

        // Estructura de datos para el PDF
        $data = [
            'title' => 'Lista de Ítems a Cotizar',
            'proyecto' => $proyecto->nombre ?? 'Nombre no disponible',
            'proyecto_fecha' => $proyecto->created_at->format('d/m/Y') ?? 'Fecha no registrada',
            'proyecto_tipo' => $proyecto->tipo == 1 ? 'Suministro' : 'Obra',
            'proyecto_direccion' => $direccion_depurada,
            'frentes' => implode(', ', $frentes),
            'fondos' => implode(', ', $fondos),
            'alturasTecho' => implode(', ', $alturasTecho),
            'areasTotales' => implode(', ', $areasTotales),
            'alturasMuros' => implode(', ', $alturasMuros),
            'canalones' => implode(', ', $canalones),
            'perimetrales' => implode(', ', $perimetrales),
            'caballetes' => implode(', ', $caballetes),
            'estructuras' => implode(', ', $estructuras),
            'cantidades' => implode(', ', $cantidades),
            'estructura_cantidad' => implode(', ', $estructura_cantidad),
            'usuario' => auth()->user()->name ?? 'Usuario desconocido',
            'usuario_first_last_name' => auth()->user()->first_last_name ?? '',
            'usuario_second_last_name' => auth()->user()->second_last_name ?? '',
            'cliente_nombre' => $cliente->nombre ?? 'No disponible',
            'cliente_correo' => $cliente->correo ?? 'No disponible',
            'cliente_direccion' => $direccion_depurada,
            'cliente_contacto_1' => $nombre_contacto_1,
            'cliente_telefono_1' => $numero_1,
            'cliente_contacto_2' => $nombre_contacto_2,
            'cliente_telefono_2' => $numero_2,
            'items_cotizar' => json_encode($lista->items_cotizar ?? []),
            'items_cotizar_data' => $items_data,
        ];

        // Generación del PDF con DOMPDF
        $pdf = Pdf::loadView('pdf.lista', $data)->setPaper('a4', 'portrait');

        return $pdf->stream('lista_items.pdf');
    }
}
