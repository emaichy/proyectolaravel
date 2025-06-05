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
        Schema::create('documentos_pacientes', function (Blueprint $table) {
            $table->id('ID_DocumentoPaciente');
            $table->string('RutaArchivo', 255)->nullable();
            $table->enum('Tipo', ['INE', 'ComprobanteDomicilio', 'CURP', 'Otro'])->default('Otro');
            $table->unsignedBigInteger('ID_Paciente');
            $table->integer('Status')->default(1);
            $table->timestamps();
            $table->foreign('ID_Paciente')->references('ID_Paciente')->on('pacientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_pacientes');
    }
};
