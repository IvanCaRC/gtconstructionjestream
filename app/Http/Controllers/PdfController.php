<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generarPdf($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $data = [
            'title' => 'Lista preliminar de suministros',
            'proyecto' => $proyecto,
            // Otros datos que necesites pasar a la vista del PDF
        ];

        $pdf = Pdf::loadView('pdf.report', $data)->setPaper('a4', 'portrait');

        return $pdf->stream('reporte_proyecto.pdf');
    }
}
