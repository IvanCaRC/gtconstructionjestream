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
            'porcentaje_venta' => 10.00,
            'precio_venta' => 100.00,
            'unidad' => 'Piezas',
            'especificaciones' => 'Especificaciones del Item A',
            'ficha_tecnica_pdf' => null,
            'estado' => true,
        ]);
    }
}

