<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo')->unique()->nullable();;
            $table->string('rfc')->unique()->nullable();;
            $table->string('bancarios')->nullable();;
            $table->integer('proyectos')->nullable();;
            $table->integer('proyectos_activos')->nullable();;
            $table->string('telefono');
            $table->date('fecha');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Definir clave foránea
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};

