<?php

namespace Database\Seeders;

use App\Models\Familia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class familiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $familia = new Familia();
        $familia->nombre = "Familia A";
        $familia->descripcion = "Familia A";
        $familia->estado = true;
        $familia->save();

        $familia2 = new Familia();
        $familia2->nombre = "Familia AB";
        $familia2->id_familia = 1;
        $familia2->estado = true;
        $familia2->descripcion = "Familia AB";
        $familia2->save();

        $familia3 = new Familia();
        $familia3->nombre = "Familia ABC";
        $familia3->id_familia = 1;
        $familia3->descripcion = "Familia ABC";
        $familia3->save();

        

        $familia4 = new Familia();
        $familia4->nombre = "Familia B";
        $familia4->estado = true;
        $familia4->descripcion = "Familia B";
        $familia4->save();

        $familia5 = new Familia();
        $familia5->nombre = "Familia BA";
        $familia5->id_familia = 4;
        $familia5->descripcion = "Familia BA";
        $familia5->save();

        $familia6 = new Familia();
        $familia6->estado = true;
        $familia6->nombre = "Familia C";
        $familia6->descripcion = "Familia C";
        $familia6->save();

        $familia7 = new Familia();
        $familia7->nombre = "Familia CA";
        $familia7->id_familia = 6;
        $familia7->descripcion = "Familia CA";
        $familia7->save();
    }
}
