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
        Schema::create('distritos', function (Blueprint $table) {
            $table->id('id_distrito'); // PK autoincremental
            $table->string('nombre', 150);
            $table->integer('nro_iglesias')->default(0); // valor por defecto
            $table->foreignId('id_pastor')->references('id_pastor')->on('pastors')->onDelete('set null');


            $table->unsignedBigInteger('id_grupo')->nullable(); // FK hacia Grupo
            $table->foreign('id_grupo')->references('id_grupo')->on('grupos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distritos');
    }
};
