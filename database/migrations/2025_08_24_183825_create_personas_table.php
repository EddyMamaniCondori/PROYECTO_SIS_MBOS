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
         Schema::create('personas', function (Blueprint $table) {
            $table->id('id_persona')->autoIncrement(); // Primary Key con nombre personalizado
            $table->string('nombre', 100);
            $table->string('ape_paterno', 100);
            $table->string('ape_materno', 100)->nullable(); // Puede ser NULL si no se registra
            $table->date('fecha_nac');
            $table->string('ci', 20)->unique(); // Carnet de Identidad único
            $table->string('celular', 10);
            $table->string('ciudad', 100);
            $table->string('zona', 100);
            $table->string('calle', 100)->nullable();
            $table->string('nro', 20)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
