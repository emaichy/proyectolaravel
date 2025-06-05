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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id('Matricula');
            $table->string('Nombre', 60);
            $table->string('ApePaterno', 50);
            $table->string('ApeMaterno', 50);
            $table->string('Foto_Alumno')->nullable();
            $table->string('Firma', 200)->nullable();
            $table->date('FechaNac')->nullable();
            $table->enum('Sexo', ['Masculino', 'Femenino'])->nullable();
            $table->string('Direccion', 100)->nullable();
            $table->string('NumeroExterior', 10)->nullable();
            $table->string('NumeroInterior', 10)->nullable();
            $table->string('CodigoPostal', 10)->nullable();
            $table->string('Pais', 50)->nullable();
            $table->enum('TipoAlumno', ['Regular', 'Irregular'])->default('Regular');
            $table->string('Telefono', 15)->nullable();
            $table->string('Curp', 18)->nullable();
            $table->unsignedBigInteger('ID_Grupo');
            $table->unsignedBigInteger('ID_Usuario');
            $table->unsignedBigInteger('ID_Estado')->nullable();
            $table->unsignedBigInteger('ID_Municipio')->nullable();
            $table->integer('Status')->default(1);
            $table->timestamps();
            $table->foreign('ID_Grupo')->references('ID_Grupo')->on('grupos')->onDelete('cascade');
            $table->foreign('ID_Usuario')->references('ID_Usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('ID_Estado')->references('ID_Estado')->on('estados')->onDelete('cascade');
            $table->foreign('ID_Municipio')->references('ID_Municipio')->on('municipios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
