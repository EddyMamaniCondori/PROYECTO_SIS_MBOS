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
        Schema::table('remesas', function (Blueprint $table) {
            //
            $table->boolean('escaneado')->default(false);
            $table->text('alerta')->nullable();
            $table->boolean('sw_cierre_rem')->default(false);
            $table->Integer('sw_det_semana')->default(0); //0 no tiene, 1 tiene pero se puede editar, 2 se bloquea la elimnacion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remesas', function (Blueprint $table) {
            $table->dropColumn(['escaneado', 'alerta', 'sw_cierre_rem', 'sw_det_semana']);
        });
    }
};
