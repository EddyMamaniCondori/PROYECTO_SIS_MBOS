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
        Schema::create('capellan', function (Blueprint $table) {
            // Claves foráneas
            $table->unsignedBigInteger('id_ue')->nullable();
            $table->unsignedBigInteger('id_pastor')->nullable();
            // Atributos de la relación
            $table->string('año');
            // Relaciones
            $table->foreign('id_ue')
                ->references('id_ue')
                ->on('unidad_educativas')
                ->onDelete('set null');
            $table->foreign('id_pastor')
                ->references('id_pastor')
                ->on('pastors')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capellan');
    }
};
