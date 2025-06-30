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
        Schema::create('citas', function (Blueprint $table) {
            $table->id('ID_Cita');
            $table->unsignedBigInteger('ID_Paciente');
            $table->unsignedBigInteger('ID_Alumno')->nullable();
            $table->date('Fecha');
            $table->time('Hora');
            $table->enum('Estado', ['Programada', 'Realizada', 'Cancelada', 'Reprogramada'])->default('Programada');
            $table->timestamps();
            $table->foreign('ID_Paciente')->references('ID_Paciente')->on('pacientes')->onDelete('cascade');
            $table->foreign('ID_Alumno')->references('Matricula')->on('alumnos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
