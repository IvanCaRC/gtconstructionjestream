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
        $proveedor = new Proveedor();
        $proveedor->nombre = "Construrama 2";
        $proveedor->descripcion = "Empresa constructora de Materiales en general";
        $proveedor->correo = "Construrama123@gmail.com";
        $proveedor->rfc = "345678909876";
        $proveedor->archivo_facturacion_pdf = null;
        $proveedor->datos_bancarios_pdf = null;
        $proveedor->estado = true;

        $proveedor->save();
    }
}

