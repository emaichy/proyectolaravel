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
        Schema::create('padecimientos', function (Blueprint $table) {
            $table->id('ID_Padecimiento');
            $table->string('NombrePadecimiento', 100);
            $table->text('Descripcion')->nullable();
            $table->integer('Status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('padecimientos');
    }
};
