<?php

namespace Database\Seeders;

use App\Models\Direccion;
use Illuminate\Database\Seeder;

class DireccionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Direccion::create([
            'cp' => '71510',
            'estado' => 'Oaxaca',
            'ciudad' => 'Oaxaca de Juárez',
            'municipio' => 'Ocotlan',
            'colonia' => 'Centro',
            'calle' => 'Boulevard Ferrocarril',
            'numero' => '421',
            'referencia' => 'Antes de llegar al puente',
            'proveedor_id' => 1,
            'cliente_id' => null,
        ]);

        Direccion::create([
            'cp' => '10000',
            'estado' => 'Veracruz',
            'ciudad' => 'Veracruz',
            'municipio' => 'Orizaba',
            'colonia' => 'Colonia Centro',
            'calle' => 'Av. Independencia',
            'numero' => '456',
            'referencia' => 'Frente al mercado',
            'proveedor_id' => 1,
            'cliente_id' => null,
        ]);
    }
}

