<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notas_evolucion', function (Blueprint $table) {
            $table->id('ID_Nota');
            $table->unsignedBigInteger('ID_Alumno');
            $table->unsignedBigInteger('ID_Paciente');
            $table->unsignedBigInteger('ID_Expediente');
            $table->unsignedBigInteger('ID_Grupo');
            $table->unsignedBigInteger('ID_Semestre');

            $table->date('fecha');
            $table->string('presion_arterial')->nullable();
            $table->string('frecuencia_cardiaca')->nullable();
            $table->string('frecuencia_respiratoria')->nullable();
            $table->string('temperatura')->nullable();
            $table->string('oximetria')->nullable();

            $table->text('tratamiento_realizado')->nullable();
            $table->text('descripcion_tratamiento')->nullable();

            $table->longText('firma_catedratico')->nullable();
            $table->longText('firma_alumno')->nullable();
            $table->longText('firma_paciente')->nullable();

            $table->string('pdf_document')->nullable();
            $table->timestamps();

            $table->foreign('ID_Alumno')->references('ID_Alumno')->on('alumnos')->onDelete('cascade');
            $table->foreign('ID_Paciente')->references('ID_Paciente')->on('pacientes')->onDelete('cascade');
            $table->foreign('ID_Expediente')->references('ID_Expediente')->on('expedientes')->onDelete('cascade');
            $table->foreign('ID_Grupo')->references('ID_Grupo')->on('grupos')->onDelete('cascade');
            $table->foreign('ID_Semestre')->references('ID_Semestre')->on('semestres')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('notas_evolucion');
    }
};
