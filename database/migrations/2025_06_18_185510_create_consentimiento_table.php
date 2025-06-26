<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('consentimientos', function (Blueprint $table) {
            $table->id();

            // Relaciones clave
            $table->unsignedBigInteger('ID_Alumno');
            $table->unsignedBigInteger('ID_Paciente');
            $table->unsignedBigInteger('ID_Expediente');
            $table->unsignedBigInteger('ID_Grupo')->nullable();
            $table->unsignedBigInteger('ID_Semestre')->nullable();


            // Información general
            $table->date('fecha');
             $table->text('declaracion')->nullable();
            $table->text('descripcion_tratamiento')->nullable();
            $table->string('alumno_tratante')->nullable();
            $table->text('docentes')->nullable();

            // Firmas base64
            $table->longText('firma_paciente')->nullable();
            $table->string('nombre_paciente')->nullable();

            $table->longText('firma_alumno')->nullable();
            $table->string('nombre_alumno')->nullable();

            $table->longText('firma_docentes')->nullable();
            $table->string('nombre_docentes')->nullable();

            $table->longText('firma_testigo')->nullable();
            $table->string('nombre_testigo')->nullable();

            $table->string('pdf_document')->nullable();

            $table->timestamps();

            // Llaves foráneas
            $table->foreign('ID_Alumno')->references('ID_Alumno')->on('alumnos')->onDelete('cascade');
            $table->foreign('ID_Paciente')->references('ID_Paciente')->on('pacientes')->onDelete('cascade');
            $table->foreign('ID_Expediente')->references('ID_Expediente')->on('expedientes')->onDelete('cascade');
            $table->foreign('ID_Grupo')->references('ID_Grupo')->on('grupos')->onDelete('cascade');
            $table->foreign('ID_Semestre')->references('ID_Semestre')->on('semestres')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('consentimientos');
    }
};
