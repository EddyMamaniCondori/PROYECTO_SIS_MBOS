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
        Schema::create('control_estudios', function (Blueprint $table) {
            $table->id('id_control'); // PK
            $table->enum('nivel', ['basic','intermedio','avanzado'])->default('basic');
            $table->integer('nro_leccion')->default(1);
            $table->date('fecha');

            // Relación con EstudianteBiblico (N:1)
            $table->unsignedBigInteger('estudiante_id');
            $table->foreign('estudiante_id')
                  ->references('id_est')
                  ->on('estudiante_biblicos')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_estudios');
    }
};
