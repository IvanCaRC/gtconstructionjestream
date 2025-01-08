<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliasTable extends Migration
{
    public function up()
    {
        Schema::create('familias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_familia_padre')->nullable(); // Llave foránea para subfamilias
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->integer('nivel')->default(1); // Nivel de anidación
            $table->boolean('estadoEliminacion')->default(false);
            $table->timestamps();

            // Definir la llave foránea
            $table->foreign('id_familia_padre')->references('id')->on('familias')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('familias');
    }
}
