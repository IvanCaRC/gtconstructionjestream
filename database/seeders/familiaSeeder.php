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
        $familia->nombre = "Metales";
        $familia->descripcion = "Todo material de acero o similar";
        $familia->save();

        $familia2 = new Familia();
        $familia2->nombre = "Laminas";
        $familia2->id_familia = 1;
        $familia2->descripcion = "Todo material de acero o similar";
        $familia2->save();

        $familia3 = new Familia();
        $familia3->nombre = "PVC Grueso";
        $familia3->id_familia = 1;
        $familia3->descripcion = "Todo material de acero o similar";
        $familia3->save();
    }
}
