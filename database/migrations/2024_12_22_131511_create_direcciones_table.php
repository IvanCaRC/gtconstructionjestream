<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDireccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->string('cp');
            $table->string('pais')->nullable();
            $table->string('estado')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('municipio')->nullable();
            $table->string('colonia')->nullable();
            $table->string('calle')->nullable();
            $table->string('numero')->nullable();
            $table->string('referencia')->nullable();
            $table->string('Latitud')->nullable();
            $table->string('Longitud')->nullable();
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete

            // Definir claves foráneas
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');

            // Asegurar que una dirección no pueda estar asociada a ambos atributos al mismo tiempo
            $table->unique(['proveedor_id', 'cliente_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direcciones');
    }
};
