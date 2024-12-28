<?php

namespace Database\Seeders;

use App\Models\ItemEspecificoHasFamilia;
use Illuminate\Database\Seeder;

class ItemEspecificoHasFamiliaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Relacionar el item con id:1 con la familia de id:1
        ItemEspecificoHasFamilia::create([
            'item_especifico_id' => 1, 
            'familia_id' => 1, 
        ]);

    }
}

