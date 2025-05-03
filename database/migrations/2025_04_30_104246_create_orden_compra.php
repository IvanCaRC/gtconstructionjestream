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
            $table->unsignedBigInteger('id_cotisacion');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_remision');
            
            $table->timestamps();
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
