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
        Schema::create('asignacion_distritos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_distrito_asignaciones')->primary();  // PK autoincremental
            $table->string('nombre', 150)->unique();
            $table->boolean('sw_estado')->default(false);
            $table->string('año')->nullable(); // identificar la gestion
            $table->foreignId('id_pastor')->nullable()
                ->constrained('pastors', 'id_pastor')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_distritos');
    }
};
