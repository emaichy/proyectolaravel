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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id('ID_Paciente');
            $table->string('Nombre', 80);
            $table->string('ApePaterno', 50);
            $table->string('ApeMaterno', 50);
            $table->date('FechaNac');
            $table->string('Sexo', 1);
            $table->string('Direccion', 200);
            $table->string('NumeroExterior');
            $table->string('NumeroInterior')->nullable();
            $table->string('CodigoPostal', 5);
            $table->string('Pais', 100);
            $table->unsignedBigInteger('ID_Estado');
            $table->unsignedBigInteger('ID_Municipio');
            $table->unsignedBigInteger('ID_Asignacion');
            $table->string('TipoPaciente', 15);
            $table->string('Foto_Paciente', 255);
            $table->timestamps();
            $table->integer('Status')->default(1);
            $table->foreign('ID_Asignacion')->references('ID_Asignacion')->on('asignacion_pacientes_alumnos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
