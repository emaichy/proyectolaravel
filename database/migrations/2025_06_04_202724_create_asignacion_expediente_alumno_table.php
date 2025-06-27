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
        Schema::create('asignacion_expediente_alumno', function (Blueprint $table) {
            $table->id('ID_Asignacion');
            $table->unsignedBigInteger('ID_Expediente');
            $table->unsignedBigInteger('ID_Alumno');
            $table->integer('Status')->default(1);
            $table->timestamps();
            $table->foreign('ID_Alumno')->references('Matricula')->on('alumnos')->onDelete('cascade');
            $table->foreign('ID_Expediente')->references('ID_Expediente')->on('expedientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_expediente_alumno');
    }
};
