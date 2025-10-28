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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id('id_gasto'); // clave primaria
            $table->decimal('monto', 20, 4)->default(0); // monto con 4 decimales
            $table->text('observacion')->nullable();
            $table->string('mes', 20);
            $table->integer('anio');
            $table->timestamps(); //created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
