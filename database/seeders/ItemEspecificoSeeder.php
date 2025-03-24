<?php

namespace Database\Seeders;

use App\Models\ItemEspecifico;
use Illuminate\Database\Seeder;

class ItemEspecificoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemEspecifico::create([
            'item_id' => 1, //Designacion de un item existente
            'marca' => 'Marca A',
            'cantidad_piezas_mayoreo' => 100,
            'cantidad_piezas_minorista' => 1,
            'porcentaje_venta_minorista' => 10.00,
            'porcentaje_venta_mayorista' => 20.00,
            'precio_venta_minorista' => 100.00,
            'precio_venta_mayorista' => 80.00,
            'unidad' => 'Piezas',
            'especificaciones' => '[{"enunciado":"Concepto 1","concepto":"Descripci\u00f3n 1"}]',
            'ficha_tecnica_pdf' => null,
            'estado' => true,
        ]);
    }
}

