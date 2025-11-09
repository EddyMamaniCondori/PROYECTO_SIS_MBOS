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
        Schema::create('visitas', function (Blueprint $table) {
            $table->id('id_visita'); // PK
            $table->date('fecha_visita'); // fecha dd/mm/yy
            $table->string('nombre_visitado');
            $table->integer('cant_present')->default(1);
            $table->string('telefono')->nullable();
            $table->time('hora')->nullable();
            $table->enum('motivo', ['Pastoral', 'Evangelismo', 'Mayordomia', 'Coordinacion', 'otros'])->nullable();
            $table->string('descripcion_lugar')->nullable(); // dirección
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
        Schema::dropIfExists('visita');
    }
};
