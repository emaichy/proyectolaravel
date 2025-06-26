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
        Schema::create('grupo_maestros', function (Blueprint $table) {
            $table->id('ID_Asignacion');
            $table->unsignedBigInteger('ID_Grupo');
            $table->unsignedBigInteger('ID_Maestro');
            $table->integer('Status')->default(1);
            $table->foreign('ID_Grupo')->references('ID_Grupo')->on('grupos')->onDelete('cascade');
            $table->foreign('ID_Maestro')->references('ID_Maestro')->on('maestros')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_maestros');
    }
};
