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
        Schema::create('bautisos', function (Blueprint $table) {
            $table->id('id_bautiso'); // PK
            $table->string('nombre');
            $table->string('ape_paterno');
            $table->string('sexo');
            $table->string('ape_materno');
            $table->date('fecha_nacimiento')->nullable();
            $table->date('fecha_bautizo');
            $table->boolean('estudiante_biblico')->default(false);

            // Relación con Iglesia (N:1)
            $table->unsignedBigInteger('iglesia_id');
            $table->foreign('iglesia_id')
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
        Schema::dropIfExists('bautisos');
    }
};
