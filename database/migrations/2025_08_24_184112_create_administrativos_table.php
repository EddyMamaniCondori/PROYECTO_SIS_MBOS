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
        Schema::create('administrativos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_persona')->primary(); // PK y FK hacia Persona
            $table->string('cargo');
            $table->string('ministerio')->nullable();

            // Relación 1:1 con Persona
            $table->foreign('id_persona')
                  ->references('id_persona')
                  ->on('personas')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrativos');
    }
};
