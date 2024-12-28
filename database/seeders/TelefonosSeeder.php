<?php

namespace Database\Seeders;

use App\Models\Telefono;
use Illuminate\Database\Seeder;

class TelefonosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $telefono = new Telefono();
        $telefono->numero = "1234567890";
        $telefono->proveedor_id = 1;
        $telefono->save();

        $telefono2 = new Telefono();
        $telefono2->numero = "0987654321";
        $telefono2->proveedor_id = 1;
        $telefono2->save();

    }
}

