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
        Schema::table('remesas_filiales', function (Blueprint $table) {
            // Cambiamos de (10,3) a (10,2)
            $table->decimal('ofrenda', 10, 2)->change();
            $table->decimal('diezmo', 10, 2)->change();
            $table->decimal('pro_templo', 10, 2)->change();
            $table->decimal('fondo_local', 10, 2)->change();
            $table->decimal('monto_remesa', 10, 2)->change();
            $table->decimal('gasto', 10, 2)->change(); // No olvides el campo gasto si lo tienes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remesas_filiales', function (Blueprint $table) {
            // Por si necesitas volver atrás
            $table->decimal('ofrenda', 10, 3)->change();
            $table->decimal('diezmo', 10, 3)->change();
            $table->decimal('pro_templo', 10, 3)->change();
            $table->decimal('fondo_local', 10, 3)->change();
            $table->decimal('monto_remesa', 10, 3)->change();
            $table->decimal('gasto', 10, 3)->change();
        });
    }
};
