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
        Schema::create('anexos_expedientes', function (Blueprint $table) {
            $table->id('ID_AnexoExpediente');
            $table->string('Nombre_Anexo', 255);
            $table->string('Ruta_Anexo', 255);
            $table->unsignedBigInteger('ID_Expediente');
            $table->integer('Status')->default(1);
            $table->foreign('ID_Expediente')->references('ID_Expediente')->on('expedientes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anexos_expedientes');
    }
};
