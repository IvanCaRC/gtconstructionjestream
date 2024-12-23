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
            $table->unsignedBigInteger('id_familia')->nullable(); // Llave foránea para subfamilias
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(false);
            $table->timestamps();

            // Definir la llave foránea
            $table->foreign('id_familia')->references('id')->on('familias')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('familias');
    }
}
;
