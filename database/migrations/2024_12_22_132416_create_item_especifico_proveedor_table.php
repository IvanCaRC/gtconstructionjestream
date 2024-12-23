<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemEspecificoProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_especifico_proveedor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_especifico_id');
            $table->unsignedBigInteger('proveedor_id');
            $table->integer('tiempo_max_entrega');
            $table->integer('tiempo_min_entrega');
            $table->decimal('precio_compra', 10, 2);
            $table->boolean('estado')->default(true);
            $table->timestamps();

            // Definir claves foráneas
            $table->foreign('item_especifico_id')->references('id')->on('item_especifico')->onDelete('cascade');
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('cascade');

            // Definir una clave única compuesta para evitar duplicados
            $table->unique(['item_especifico_id', 'proveedor_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_especifico_proveedor');
    }
};

