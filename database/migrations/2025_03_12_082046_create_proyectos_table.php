<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('direccion_id')->nullable();
            $table->integer('proceso'); // 0 Creando listas, 1 Creando cotizacion, 2 Cotizado, 3 En proceso de venta, 4 Venta terminada, 5 Cancelado
            $table->string('nombre');
            $table->integer('preferencia')->nullable();
            $table->integer('listas');
            $table->integer('cotisaciones');
            $table->integer('ordenes');
            $table->integer('tipo');
            $table->integer('estado');
            $table->string('archivo')->nullable();
            $table->text('items_cotizar')->nullable();
            $table->text('datos_medidas')->nullable();
            $table->text('datos_adicionales')->nullable();
            $table->integer('culminacion')->nullable(); // 0 = Cancelado, 1 = Concretado
            $table->text('motivo_finalizacion')->nullable(); // Explicacion del motivo de finalizacion
            $table->text('motivo_detallado')->nullable(); // Explicacion especifica de la finalizacion
            $table->date('fecha');

            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('direccion_id')->references('id')->on('direcciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
