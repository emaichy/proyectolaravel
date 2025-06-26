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
    {/**
        *Schema::create('materias', function (Blueprint $table) {
         *   $table->id('ID_Materia');
          *  $table->string('NombreMateria', 200);
           * $table->unsignedBigInteger('ID_Maestro');
            *$table->unsignedBigInteger('ID_Semestre');
            *$table->integer('Status')->default(1);
            *$table->foreign('ID_Maestro')->references('ID_Maestro')->on('maestros')->onDelete('cascade');
            *$table->foreign('ID_Semestre')->references('ID_Semestre')->on('semestres')->onDelete('cascade');
            *$table->timestamps();
        *});*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
