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
        Schema::create('orden_compra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_provedor'); // Clave foránea
            $table->unsignedBigInteger('id_cotizacion');
            $table->unsignedBigInteger('id_usuario');
            $table->string('nombre')->nullable();
            $table->string('formaPago')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('monto')->nullable();
            $table->string('montoPagar')->nullable();
            $table->text('items_cotizar_proveedor')->nullable();
            $table->integer('estado');

            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_cotizacion')->references('id')->on('cotizaciones')->onDelete('cascade');
            $table->foreign('id_provedor')->references('id')->on('direcciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_compra');
    }
};
