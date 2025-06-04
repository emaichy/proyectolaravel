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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id('ID_Grupo');
            $table->string('NombreGrupo', 20);
            $table->unsignedBigInteger('ID_Maestro');
            $table->unsignedBigInteger('ID_Semestre');
            $table->timestamps();
$table->integer('Status')->default(1);
            $table->foreign('ID_Maestro')->references('ID_Maestro')->on('maestros');
            $table->foreign('ID_Semestre')->references('ID_Semestre')->on('semestres');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
