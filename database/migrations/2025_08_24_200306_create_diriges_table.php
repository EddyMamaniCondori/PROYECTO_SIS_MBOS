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
        Schema::create('diriges', function (Blueprint $table) {
            // Claves foráneas
            $table->unsignedBigInteger('id_distrito');
            $table->unsignedBigInteger('id_pastor');

            // Atributos de la relación
            $table->date('fecha_asignacion');
            $table->date('fecha_finalizacion')->nullable(); // puede estar vacío si sigue activo

            $table->timestamps();

            // Relaciones
            $table->foreign('id_distrito')
                ->references('id_distrito')
                ->on('distritos')
                ->onDelete('set null');

            $table->foreign('id_pastor')
                ->references('id_pastor')
                ->on('pastors')
                ->onDelete('set null');

            // Para evitar duplicados de asignaciones
            $table->unique(['id_distrito', 'id_pastor', 'fecha_asignacion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diriges');
    }
};
