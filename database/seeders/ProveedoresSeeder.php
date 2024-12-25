<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Proveedor::create([
            'nombre' => 'Construrama',
            'descripcion' => 'Empresa constructora de Materiales en general',
            'correo' => 'Construrama123@gmail.com',
            'rfc' => '345678909876',
            'archivo_facturacion_pdf' => null,
            'datos_bancarios_pdf' => null,
            'estado' => true,
        ]);
    }
}

