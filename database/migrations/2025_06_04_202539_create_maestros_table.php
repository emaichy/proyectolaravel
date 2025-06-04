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
        Schema::create('maestros', function (Blueprint $table) {
            $table->id('ID_Maestro');
            $table->string('Nombre', 60);
            $table->string('ApePaterno', 50);
            $table->string('ApeMaestro', 50);
            $table->string('Especialidad', 60);
            $table->unsignedBigInteger('ID_Usuario');
            $table->timestamps();
            $table->integer('Status')->default(1);
            $table->foreign('ID_Usuario')->references('ID_Usuario')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maestros');
    }
};
