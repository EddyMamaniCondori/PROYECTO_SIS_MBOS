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
        Schema::create('desafios', function (Blueprint $table) {
            $table->id('id_desafio'); // clave primaria personalizada

            // Campos principales
            $table->integer('desafio_bautizo')->default(0);
            $table->integer('bautizos_alcanzados')->default(0);
            $table->year('anio'); // solo almacena el año (ej: 2025)
            $table->boolean('estado')->default(false);

            // Relaciones
            $table->unsignedBigInteger('id_distrito')->nullable();
            $table->unsignedBigInteger('id_pastor')->nullable();

            // Timestamps
            $table->timestamps();

            // 🔹 Llaves foráneas
            $table->foreign('id_distrito')->references('id_distrito')->on('distritos')->onDelete('set null');
            $table->foreign('id_pastor')->references('id_pastor')->on('pastors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desafios');
    }
};
