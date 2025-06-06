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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('name');
            $table->string('first_last_name');
            $table->string('second_last_name')->nullable();
            $table->string('email')->unique();
            $table->string('number')->nullable();
            $table->boolean('status');
            $table->boolean('estadoEliminacion')->default(false);
            $table->string('password'); 
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('lista')->nullable();
            $table->unsignedBigInteger('cotizaciones')->nullable();
            $table->rememberToken();
            $table->timestamps();        
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
