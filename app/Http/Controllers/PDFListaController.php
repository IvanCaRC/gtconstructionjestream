<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
            'usuario' => Session::get('usuario', 'Usuario desconocido'),
            'usuario_first_last_name' => Session::get('usuario_first_last_name', ''),
            'usuario_second_last_name' => Session::get('usuario_second_last_name', ''),
            'cliente_nombre' => Session::get('cliente_nombre', 'No disponible'),
            'cliente_correo' => Session::get('cliente_correo', 'No disponible'),
            'cliente_direccion' => Session::get('cliente_direccion', 'No registrada'),
            'cliente_telefono' => Session::get('cliente_telefono', 'No disponible'),
            'cliente_contacto' => Session::get('cliente_contacto', 'No registrado'),
            // 'items_cotizar' => Session::get('items_cotizar', 'No hay ítems registrados'),
            'items_cotizar_data' => Session::get('items_cotizar_data', []),
            'items_cotizar_temporales' => Session::get('items_cotizar_temporales', 'No hay ítems temporales'),
        ];

        $pdf = Pdf::loadView('pdf.lista', $data)->setPaper('a4', 'portrait');

        return $pdf->stream('lista_items.pdf');
    }
}
