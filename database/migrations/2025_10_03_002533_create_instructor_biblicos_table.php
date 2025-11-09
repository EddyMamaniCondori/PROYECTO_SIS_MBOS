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
        Schema::create('instructor_biblicos', function (Blueprint $table) {
            $table->id('id_instructor'); // PK
            $table->string('nombre');
            $table->string('ape_paterno');
            $table->string('ape_materno')->nullable();
            $table->enum('sexo', ['M','F']);
            $table->date('fecha_nacimiento')->nullable();
            $table->string('celular')->nullable();
            $table->date('fecha_registro')->nullable();
            // Relación con Iglesia
            $table->unsignedBigInteger('id_iglesia');
            $table->foreign('id_iglesia')
                  ->references('id_iglesia')
                  ->on('iglesias')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_biblicos');
    }
};
