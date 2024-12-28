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
        $familia->nombre = "metales";
        $familia->descripcion = "Todo material de RRR o similar";
        $familia->estado = true;
        $familia->save();

        $familia2 = new Familia();
        $familia2->nombre = "metales21";
        $familia2->id_familia = 1;
        $familia2->descripcion = "Todo material de EEE o similar";
        $familia2->save();

        $familia3 = new Familia();
        $familia3->nombre = "metales22";
        $familia3->id_familia = 1;
        $familia3->descripcion = "Todo material de DDD o similar";
        $familia3->save();

        

        $familia4 = new Familia();
        $familia4->nombre = "AZULEJOS";
        $familia4->descripcion = "Todo material de SSS o similar";
        $familia4->save();

        $familia5 = new Familia();
        $familia5->nombre = "AZULEJOS 21";
        $familia5->id_familia = 4;
        $familia5->descripcion = "Todo material de SSS o similar";
        $familia5->save();

        $familia6 = new Familia();
        $familia6->nombre = "metales23";
        $familia6->id_familia = 2;
        $familia6->descripcion = "Todo material de DDD o similar";
        $familia6->save();

        $familia7 = new Familia();
        $familia7->nombre = "metales231";
        $familia7->id_familia = 6;
        $familia7->descripcion = "Todo material de DDD o similar";
        $familia7->save();
    }
}
