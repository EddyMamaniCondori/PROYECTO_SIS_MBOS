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
        Schema::create('asigna_desafios', function (Blueprint $table) {
            $table->id('id_asigna_desafio');
            $table->unsignedBigInteger('id_desafio');
            $table->unsignedBigInteger('id_desafio_evento');

            // Atributos adicionales de la relación
            $table->unsignedInteger('desafio')->default(0);
            $table->unsignedInteger('alcanzado')->default(0);

            $table->timestamps();

            // 🔹 Claves foráneas
            $table->foreign('id_desafio')
                ->references('id_desafio')
                ->on('desafios')
                ->onDelete('cascade');

            $table->foreign('id_desafio_evento')
                ->references('id_desafio_evento')
                ->on('desafio_eventos')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asigna_desafios');
    }
};
