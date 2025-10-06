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
        Schema::create('estudiante_biblicos', function (Blueprint $table) {
            $table->id('id_est'); // PK
            $table->string('nombre');
            $table->string('ape_paterno');
            $table->string('ape_materno');
            $table->enum('sexo', ['M','F'])->nullable();
            $table->string('opcion_contacto')->nullable(); // email, teléfono, etc.
            $table->integer('edad')->nullable();
            $table->string('celular')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('ci')->nullable()->unique();
            $table->string('curso_biblico_usado')->nullable();
            $table->boolean('bautizado')->default(false);
            // FK hacia Iglesia
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
        Schema::dropIfExists('estudiante_biblicos');
    }
};
