<?php

namespace Database\Seeders;

use App\Models\ItemEspecificoProveedor;
use Illuminate\Database\Seeder;

class ItemEspecificoProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Designar al proveedor 1 con el item 1
        ItemEspecificoProveedor::create([
            'item_especifico_id' => 1, 
            'proveedor_id' => 1, 
            'tiempo_max_entrega' => 15,
            'tiempo_min_entrega' => 2,
            'precio_compra' => 8.00,
            'estado' => true,
        ]);
    }
}

