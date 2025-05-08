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
        Schema::create('orden_venta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente'); // Clave foránea
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_cotizacion');
            $table->unsignedBigInteger('direccion_id')->nullable();
            $table->string('nombre')->nullable();
            $table->string('formaPago')->nullable();
            $table->string('metodoPago')->nullable();
            $table->string('monto')->nullable();
            $table->string('montoPagar')->nullable();
            $table->integer('estado');
            $table->timestamps();
            

            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_cotizacion')->references('id')->on('cotizaciones')->onDelete('cascade');
            $table->foreign('direccion_id')->references('id')->on('direcciones')->onDelete('cascade');

        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_venta');
    }
};
