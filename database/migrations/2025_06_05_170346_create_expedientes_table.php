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
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id('ID_Expediente');
            $table->unsignedBigInteger('ID_Asignacion');
            $table->enum('TipoExpediente', ['Adulto', 'Pediatrico']);
            $table->integer('Status')->default(1);
            $table->timestamps();
            $table->foreign('ID_Asignacion')->references('ID_Asignacion')->on('asignaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
