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
         Schema::create('personales', function (Blueprint $table) {
            $table->unsignedBigInteger('id_personal')->primary()->unique();
            $table->foreign('id_personal')
                ->references('id_persona')
                ->on('personas')
                ->onDelete('cascade');
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_finalizacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personales');
    }
};
