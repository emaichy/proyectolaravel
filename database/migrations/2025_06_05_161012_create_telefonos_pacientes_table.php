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
        Schema::create('telefonos_pacientes', function (Blueprint $table) {
            $table->id('ID_TelefonoPaciente');
            $table->string('Telefono', 10);
            $table->enum('Tipo', ['Casa', 'Celular', 'Trabajo'])->default('Casa');
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
        Schema::dropIfExists('telefonos_pacientes');
    }
};
