<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemEspecificoHasFamiliaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_especifico_has_familia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_especifico_id');
            $table->unsignedBigInteger('familia_id');
            $table->timestamps();

            // Definir claves foráneas
            $table->foreign('item_especifico_id')->references('id')->on('item_especifico')->onDelete('cascade');
            $table->foreign('familia_id')->references('id')->on('familias')->onDelete('cascade');

            // Definir una clave única compuesta para evitar duplicados
            $table->unique(['item_especifico_id', 'familia_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
        * @return void
        */
    public function down()
    {
        Schema::dropIfExists('item_especifico_has_familia');
    }
};
