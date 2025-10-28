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
        Schema::create('remesas', function (Blueprint $table) {
            $table->id('id_remesa'); // Clave primaria
            
            $table->boolean('cierre')->default(false);
            $table->boolean('deposito')->default(false);
            $table->boolean('documentacion')->default(false);
            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_limite')->nullable();
            $table->string('estado', 50)->nullable();
            $table->text('observacion')->nullable();
            $table->string('mes', 20);
            $table->integer('anio');
            $table->decimal('monto', 20, 4)->default(0);
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remesas');
    }
};
