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
            $table->unsignedBigInteger('id_distrito', true)->primary()->unique(); // PK autoincremental
            $table->string('nombre', 150)->unique();
            $table->integer('nro_iglesias')->default(0); // valor por defecto
            $table->boolean('estado')->default(true); // eliminacion logica
            $table->boolean('sw_cambio')->default(false);
            $table->string('año')->nullable(); // identificar la gestion

            $table->date('fecha_asignacion')->nullable(); // ES PASTOR ACTUAL DESDE QUE FECHA ESTA.
            $table->foreignId('id_pastor')->nullable()
                ->constrained('pastors', 'id_pastor')
                ->nullOnDelete();

            $table->foreignId('id_grupo')->nullable()
                ->constrained('grupos', 'id_grupo')
                ->nullOnDelete()
                ->cascadeOnUpdate(); // Actualiza la FK si cambia el ID del grupo

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
