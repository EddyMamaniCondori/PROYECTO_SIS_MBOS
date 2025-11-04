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
        Schema::create('puntualidades', function (Blueprint $table) {
            $table->id('id_puntualidad');
            $table->year('anio');
            $table->unsignedBigInteger('id_iglesia');
            $table->timestamps();
            $table->foreign('id_iglesia')->references('id_iglesia')->on('iglesias')->onDelete('cascade');
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntualidades');
    }
};
