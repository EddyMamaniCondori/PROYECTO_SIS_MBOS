<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Agregamos el campo 'registrado' (tipo switch/boolean)
        Schema::table('remesas', function (Blueprint $table) {
            $table->boolean('registrado')->default(false)->after('estado');
            $table->tinyInteger('puntualidad')->default(0);
        });

        // 2. Corregimos las horas de los datos antiguos
        // Buscamos los que tengan exactamente las 00:00:00 y los movemos al final del día
        // Esto evita que arruinen tu ranking de puntualidad.
        DB::statement("
            UPDATE remesas 
            SET fecha_entrega = fecha_entrega + interval '23 hours 59 minutes 59 seconds' 
            WHERE CAST(fecha_entrega AS time) = '00:00:00'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remesas', function (Blueprint $table) {
            $table->dropColumn('registrado', 'puntualidad');
        });
    }
};
