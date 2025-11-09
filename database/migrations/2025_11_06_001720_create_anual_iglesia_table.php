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
        Schema::create('anual_iglesias', function (Blueprint $table) {
            $table->id('id_desafio_iglesia');
            $table->unsignedInteger('desafio_instructores')->nullable()->default(0);
            $table->unsignedInteger('instructores_alcanzados')->nullable()->default(0);
            $table->unsignedInteger('desafio_estudiantes')->nullable()->default(0);
            $table->unsignedInteger('estudiantes_alcanzados')->nullable()->default(0);
            $table->unsignedBigInteger('id_desafio')->nullable();
            $table->unsignedBigInteger('id_iglesia')->nullable();
            $table->timestamps();
            $table->foreign('id_desafio')->references('id_desafio')->on('desafios')->onDelete('set null');
            $table->foreign('id_iglesia')->references('id_iglesia')->on('iglesias')->onDelete('set null');
        });
    }

    /**  
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anual_iglesias');
    }
};
