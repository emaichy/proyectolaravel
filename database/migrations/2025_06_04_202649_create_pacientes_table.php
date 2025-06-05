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
            $table->enum('Sexo', ['Masculino', 'Femenino'])->nullable();
            $table->string('Direccion', 100)->nullable();
            $table->string('NumeroExterior', 10)->nullable();
            $table->string('NumeroInterior', 10)->nullable();
            $table->string('CodigoPostal', 10)->nullable();
            $table->string('Pais', 100);
            $table->string('TipoPaciente', 15);
            $table->string('Foto_Paciente', 255);
            $table->unsignedBigInteger('ID_Estado');
            $table->unsignedBigInteger('ID_Municipio');
            $table->integer('Status')->default(1);
            $table->timestamps();
            $table->foreign('ID_Estado')->references('ID_Estado')->on('estados')->onDelete('cascade');
            $table->foreign('ID_Municipio')->references('ID_Municipio')->on('municipios')->onDelete('cascade');
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
