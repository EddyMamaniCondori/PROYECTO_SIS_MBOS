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
        Schema::create('iglesias', function (Blueprint $table) {
            $table->id('id_iglesia'); // PK autoincremental
            $table->string('nombre'); // no nulo
            $table->integer('codigo')->default(0); // no nulo, default 0
            $table->boolean('estado')->default(true); 
            $table->integer('feligresia')->default(0); // no nulo, default 0
            $table->integer('feligresia_asistente')->default(0); // no nulo, default 0
            $table->string('ciudad')->nullable();
            $table->string('zona')->nullable();
            $table->string('calle')->nullable();
            $table->string('nro')->nullable();
            $table->enum('lugar', ['ALTIPLANO', 'EL ALTO'])->nullable();
            $table->enum('tipo', ['Iglesia', 'Grupo','Filial'])->nullable();
            $table->unsignedBigInteger('distrito_id')->nullable();
            $table->foreign('distrito_id')
                ->references('id_distrito')
                ->on('distritos')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iglesias');
    }
};
