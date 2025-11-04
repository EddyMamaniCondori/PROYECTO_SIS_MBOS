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
        Schema::create('justificas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_puntualidad');
            $table->tinyInteger('mes');  
            $table->unsignedBigInteger('id_remesa');

            $table->timestamps();

            // Clave primaria compuesta
            $table->primary(['id_puntualidad', 'mes', 'id_remesa']);

            // Foreign keys
            $table->foreign(['id_puntualidad', 'mes'])
                ->references(['id_puntualidad', 'mes'])
                ->on('mes')
                ->onDelete('cascade');

            $table->foreign('id_remesa')
                ->references('id_remesa')
                ->on('remesas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('justificas');
    }
};
