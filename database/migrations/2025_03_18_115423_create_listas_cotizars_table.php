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
        Schema::create('listas_cotizars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('id_usuario_compras')->nullable();
            $table->string('nombre')->nullable();
            $table->integer('estado');
            $table->text('items_cotizar')->nullable();
            $table->text('items_cotizar_temporales')->nullable();
            $table->timestamps();

            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_usuario_compras')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listas_cotizars');
    }
};
