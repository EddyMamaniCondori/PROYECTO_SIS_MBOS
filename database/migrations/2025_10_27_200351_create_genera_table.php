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
        Schema::create('generas', function (Blueprint $table) {
            // Claves foráneas
            $table->unsignedBigInteger('id_iglesia');
            $table->unsignedBigInteger('id_remesa');

            // Atributos adicionales
            $table->tinyInteger('mes')->unsigned()->comment('1=Enero, 2=Febrero, ..., 12=Diciembre');
            $table->year('anio');

            // Clave primaria compuesta
            $table->primary(['id_iglesia', 'id_remesa']);


                    // 🔹 Restricción única (para que no se repita la misma combinación)
            $table->unique(['id_iglesia', 'id_remesa', 'anio', 'mes'], 'genera_unica_por_mes');

            // Relaciones foráneas
            $table->foreign('id_iglesia')
                ->references('id_iglesia')->on('iglesias')
                ->onDelete('cascade');

            $table->foreign('id_remesa')
                ->references('id_remesa')->on('remesas')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generas');
    }
};
