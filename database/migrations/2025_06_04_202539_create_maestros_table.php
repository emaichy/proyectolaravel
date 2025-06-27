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
            $table->string('Foto_Maestro', 255)->nullable();
            $table->string('Firma', 200)->nullable();
            $table->date('FechaNac')->nullable();
            $table->enum('Sexo', ['Masculino', 'Femenino'])->nullable();
            $table->string('Direccion', 100)->nullable();
            $table->string('NumeroExterior', 10)->nullable();
            $table->string('NumeroInterior', 10)->nullable();
            $table->string('CodigoPostal', 10)->nullable();
            $table->string('Pais', 50)->nullable();
            $table->string('Telefono', 15)->nullable();
            $table->string('CedulaProfesional', 20)->nullable();
            $table->unsignedBigInteger('ID_Estado')->nullable();
            $table->unsignedBigInteger('ID_Municipio')->nullable();
            $table->unsignedBigInteger('ID_Usuario');
            $table->integer('Status')->default(1);
            $table->timestamps();
            $table->foreign('ID_Usuario')->references('ID_Usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('ID_Municipio')->references('ID_Municipio')->on('municipios')->onDelete('cascade');
            $table->foreign('ID_Estado')->references('ID_Estado')->on('estados')->onDelete('cascade');
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
