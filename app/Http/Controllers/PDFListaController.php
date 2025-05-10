<?php

namespace App\Http\Controllers;

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
            // 'cliente_direccion' => Session::get('cliente_direccion', 'No registrada'),
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
}
