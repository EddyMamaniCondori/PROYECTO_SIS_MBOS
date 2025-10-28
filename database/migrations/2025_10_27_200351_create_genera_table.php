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
        Schema::create('genera', function (Blueprint $table) {
            // Claves primarias compuestas
            $table->unsignedBigInteger('id_iglesia');
            $table->unsignedBigInteger('id_remesa');
            $table->unsignedBigInteger('id_gasto');

            // Atributos de la relación
            $table->string('mes', 20);
            $table->integer('anio');

            $table->timestamps();

            // Clave primaria compuesta
            $table->primary(['id_iglesia', 'id_remesa', 'id_gasto']);

            // Claves foráneas
            $table->foreign('id_iglesia')->references('id_iglesia')->on('iglesias')->onDelete('cascade');
            $table->foreign('id_remesa')->references('id_remesa')->on('remesas')->onDelete('cascade');
            $table->foreign('id_gasto')->references('id_gasto')->on('gastos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genera');
    }
};
