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
        Schema::create('fotografias_pacientes', function (Blueprint $table) {
            $table->id('ID_Fotografia');
            $table->string('RutaArchivo', 255)->nullable();
            $table->string('Descripcion', 255)->nullable();
            $table->enum('Tipo', ['Centro', 'Perfil Izquierdo', 'Perfil Derecho', 'Otro'])->default('Otro');
            $table->unsignedBigInteger('ID_Paciente');
            $table->integer('Status')->default(1);
            $table->timestamps();
            $table->foreign('ID_Paciente')->references('ID_Paciente')->on('pacientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotografias_pacientes');
    }
};
