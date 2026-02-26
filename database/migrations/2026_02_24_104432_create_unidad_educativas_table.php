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
        Schema::create('unidad_educativas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_ue', true)->primary()->unique(); // PK autoincremental
            $table->string('nombre', 150)->unique();
            $table->boolean('estado')->default(true); // eliminacion logica
            $table->boolean('sw_cambio')->default(false);
            $table->string('año')->nullable(); // identificar la gestion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidad_educativas');
    }
};
