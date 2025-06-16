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
            $table->unsignedBigInteger('ID_Maestro')->nullable();
            $table->unsignedBigInteger('ID_Semestre')->nullable();
            $table->integer('Status')->default(1);
            $table->timestamps();
            $table->foreign('ID_Maestro')->references('ID_Maestro')->on('maestros')->onDelete('cascade');
            $table->foreign('ID_Semestre')->references('ID_Semestre')->on('semestres')->onDelete('cascade');
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
