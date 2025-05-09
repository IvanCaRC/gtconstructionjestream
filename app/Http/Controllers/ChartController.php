<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;

class ChartController extends Controller
{
    public function getProjectData()
    {
        $data = Proyecto::select('nombre', 'estado as porcentaje_cierre')->get();
        return response()->json($data);
    }
}
