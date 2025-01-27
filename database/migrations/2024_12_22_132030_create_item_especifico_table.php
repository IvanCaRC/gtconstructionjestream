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
            $table->string('image')->nullable();
            $table->string('marca');
            $table->integer('cantidad_piezas_mayoreo');
            $table->integer('cantidad_piezas_minorista');
            $table->decimal('porcentaje_venta_minorista', 5, 2);
            $table->decimal('porcentaje_venta_mayorista', 5, 2);
            $table->decimal('precio_venta_minorista', 10, 2)->nullable();
            $table->decimal('precio_venta_mayorista', 10, 2)->nullable();
            $table->string('unidad')->nullable();
            $table->integer('stock')->nullable();
            $table->text('especificaciones')->nullable();
            $table->string('ficha_tecnica_pdf')->nullable();
            $table->boolean('estado')->default(true);
            $table->boolean('estado_eliminacion')->default(true);
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