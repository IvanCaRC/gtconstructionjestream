<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemEspecificoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_especifico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->string('marca');
            $table->integer('cantidad_piezas_mayoreo');
            $table->integer('cantidad_piezas_minorista');
            $table->decimal('porcentaje_venta', 5, 2);
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->string('unidad');
            $table->text('especificaciones')->nullable();
            $table->string('ficha_tecnica_pdf')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            // Definir clave foránea
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_especifico');
    }
};