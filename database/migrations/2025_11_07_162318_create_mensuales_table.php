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
        Schema::create('mensuales', function (Blueprint $table) {
            $table->id('id_mensual');
            
            $table->unsignedInteger('mes');          // Número del mes (1-12)
            $table->unsignedInteger('anio');         // Año
            $table ->Integer('desafio_visitas')->default(0);
            $table ->Integer('visitas_alcanzadas')->default(0);
            $table->date('fecha_limite');
            $table->unsignedBigInteger('id_desafio'); // Relación con desafio

            $table->timestamps();

            // 🔹 Clave foránea
            $table->foreign('id_desafio')
                ->references('id_desafio')
                ->on('desafios')
                ->onDelete('cascade'); // si se elimina el desafío, se eliminan los mensuales relacionados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensuales');
    }
};
