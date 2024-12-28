<?php

namespace Database\Seeders;

use App\Models\ProveedorHasFamilia;
use Illuminate\Database\Seeder;

class ProveedorHasFamiliaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Relacionar al proveedor id:1, con la familia de id:1
        ProveedorHasFamilia::create([
            'proveedor_id' => 1, 
            'familia_id' => 1, 
        ]);

    }
}

