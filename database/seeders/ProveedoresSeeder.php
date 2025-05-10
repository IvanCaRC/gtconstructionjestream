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
        $proveedor->rfc = "YYXD521112RO6";
        $proveedor->archivo_facturacion_pdf = null;
        $proveedor->datos_bancarios_pdf = null;
        $proveedor->estado = true;

        $proveedor->save();

        $proveedor2 = new Proveedor();
        $proveedor2->nombre = "Prveedor 2";
        $proveedor2->descripcion = "Descripcion de proveedor 2";
        $proveedor2->correo = "proveedor2@gmail.com";
        $proveedor2->rfc = "DLKT490112EI9";
        $proveedor2->archivo_facturacion_pdf = null;
        $proveedor2->datos_bancarios_pdf = null;
        $proveedor2->estado = true;

        $proveedor2->save();

        $proveedor3 = new Proveedor();
        $proveedor3->nombre = "Prveedor 3";
        $proveedor3->descripcion = "Descripcion de proveedor 3";
        $proveedor3->correo = "proveedor3@gmail.com";
        $proveedor3->rfc = "ETM221205YK2";
        $proveedor3->archivo_facturacion_pdf = null;
        $proveedor3->datos_bancarios_pdf = null;
        $proveedor3->estado = true;

        $proveedor3->save();
    }
}

