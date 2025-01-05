<?php

namespace Database\Seeders;

use App\Models\Familia;
use Illuminate\Database\Seeder;

class FamiliaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nivel 1
        $familia1 = new Familia();
        $familia1->nombre = "Familia A";
        $familia1->descripcion = "Familia A";
        $familia1->nivel = 1;
        $familia1->save();

        // Nivel 2
        $familia2 = new Familia();
        $familia2->nombre = "Familia AA";
        $familia2->descripcion = "Familia AA";
        $familia2->nivel = 2;
        $familia2->id_familia_padre = $familia1->id;
        $familia2->save();

        $familia3 = new Familia();
        $familia3->nombre = "Familia AB";
        $familia3->descripcion = "Familia AB";
        $familia3->nivel = 2;
        $familia3->id_familia_padre = $familia1->id;
        $familia3->save();

        // Nivel 1
        $familia4 = new Familia();
        $familia4->nombre = "Familia B";
        $familia4->descripcion = "Familia B";
        $familia4->nivel = 1;
        $familia4->save();

        // Nivel 2
        $familia5 = new Familia();
        $familia5->nombre = "Familia BA";
        $familia5->descripcion = "Familia BA";
        $familia5->nivel = 2;
        $familia5->id_familia_padre = $familia4->id;
        $familia5->save();

        // Nivel 1
        $familia6 = new Familia();
        $familia6->nombre = "Familia C";
        $familia6->descripcion = "Familia C";
        $familia6->nivel = 1;
        $familia6->save();

        // Nivel 2
        $familia7 = new Familia();
        $familia7->nombre = "Familia CA";
        $familia7->descripcion = "Familia CA";
        $familia7->nivel = 2;
        $familia7->id_familia_padre = $familia6->id;
        $familia7->save();

        // Nivel 3
        $familia8 = new Familia();
        $familia8->nombre = "Familia AAB";
        $familia8->descripcion = "Familia AAB";
        $familia8->nivel = 3;
        $familia8->id_familia_padre = $familia2->id;
        $familia8->save();

        $familia9 = new Familia();
        $familia9->nombre = "Familia ABA";
        $familia9->descripcion = "Familia ABA";
        $familia9->nivel = 3;
        $familia9->id_familiaadre = $familia3->id;
        $familia9->save();

        // Nivel 4
        $familia10 = new Familia();
        $familia10->nombre = "Familia AABA";
        $familia10->descripcion _p= "Familia AABA";
        $familia10->nivel = 4;
        $familia10->id_familia_padre = $familia8->id;
        $familia10->save();

        $familia11 = new Familia();
        $familia11->nombre = "Familia ABAA";
        $familia11->descripcion = "Familia ABAA";
        $familia11->nivel = 4;
        $familia11->id_familia_padre = $familia9->id;
        $familia11->save();

        // Nivel 5
        $familia12 = new Familia();
        $familia12->nombre = "Familia AABAA";
        $familia12->descripcion = "Familia AABAA";
        $familia12->nivel = 5;
        $familia12->id_familia_padre = $familia10->id;
        $familia12->save();
    }
}
