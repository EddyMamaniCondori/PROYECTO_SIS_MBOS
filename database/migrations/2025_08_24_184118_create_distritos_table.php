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
            $table->boolean('sw_cambio')->default(false);
            $table->boolean('sw_estado')->default(false);
            $table->string('año')->nullable();
            $table->foreignId('id_pastor')->nullable()
                ->constrained('pastors', 'id_pastor')
                ->nullOnDelete();

            $table->foreignId('id_grupo')->nullable()
                ->constrained('grupos', 'id_grupo')
                ->cascadeOnDelete();

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
