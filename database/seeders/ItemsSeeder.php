<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'nombre' => 'Lamina corrugada',
            'descripcion' => 'Lamina corrugada de 500g, grosor de 1 pulgada',
        ]);

        Item::create([
            'nombre' => 'PVC con bobina de refuerzo',
            'descripcion' => 'PVC compuesto con bobina de refuerzo para alta presion',
        ]);

    }
}

