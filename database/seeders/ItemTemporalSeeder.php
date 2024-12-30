<?php

namespace Database\Seeders;

use App\Models\ItemTemporal;
use Illuminate\Database\Seeder;

class ItemTemporalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemTemporal::create([
            'item_id' => 2, // Suponiendo que el item con ID 2 existe
        ]);
    }
}


