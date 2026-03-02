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
        Schema::create('visita_capellans', function (Blueprint $table) {
             $table->id('id_visita_cape'); // PK
            $table->date('fecha_visita'); // fecha dd/mm/yy
            $table->string('nombre_visitado');
            $table->integer('cant_present')->default(1);
            $table->string('telefono')->nullable();
            $table->time('hora')->nullable();
            $table->enum('motivo', ['Pastoral', 'Evangelismo', 'Mayordomia', 'Coordinacion', 'otros'])->nullable();
            $table->string('descripcion_lugar')->nullable(); // dirección
            // Relación con Iglesia
            $table->unsignedBigInteger('id_ue');
            $table->foreign('id_ue')
                  ->references('id_ue')
                  ->on('unidad_educativas')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('id_pastor');
            $table->foreign('id_pastor')
                  ->references('id_pastor')
                  ->on('pastors')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita_capellans');
    }
};
