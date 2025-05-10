<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesTable extends Migration
{
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lista_cotizar_id'); // Clave foránea
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('id_usuario_compras')->nullable();
            $table->string('nombre')->nullable();
            $table->integer('estado');
            $table->text('items_cotizar_proveedor')->nullable();
            $table->text('items_cotizar_stock')->nullable();
            $table->timestamps();

            // Definición de claves foráneas
            $table->foreign('lista_cotizar_id')->references('id')->on('listas_cotizars')->onDelete('cascade');
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_usuario_compras')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cotizaciones');
    }
}
