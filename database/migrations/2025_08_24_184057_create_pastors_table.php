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
        Schema::create('pastors', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pastor')->primary()->unique(); // PK y FK a la vez
            $table->date('fecha_ordenacion'); // Fecha de ordenación
            $table->boolean('ordenado')->default(false); // true/false o 1/0
            $table->string('cargo', 100); // Cargo que ocupa
            $table->integer('nro_distritos'); // Número de distritos asignados
            
            // Definir la FK hacia personas(id_persona)
            $table->foreign('id_pastor')
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
        Schema::dropIfExists('pastors');
    }
};
