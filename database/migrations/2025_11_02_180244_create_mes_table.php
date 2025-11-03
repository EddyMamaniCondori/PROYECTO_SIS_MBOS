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
        Schema::create('mes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_puntualidad');
            $table->string('mes', 20);

            $table->enum('tipo', ['0', '1', '2'])->default('0');

            // Clave primaria compuesta
            $table->primary(['id_puntualidad', 'mes']);

            // Clave foránea
            $table->foreign('id_puntualidad')
                ->references('id_puntualidad')
                ->on('puntualidades')
                ->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mes');
    }
};
