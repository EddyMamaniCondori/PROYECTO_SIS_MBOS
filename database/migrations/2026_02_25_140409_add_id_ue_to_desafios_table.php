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
        Schema::table('desafios', function (Blueprint $table) {
            //// Creamos la columna id_ue como bigInteger, sin signo y que permita nulos
            $table->unsignedBigInteger('id_ue')->nullable()->after('id_pastor'); 

            // Definimos la clave foránea
            $table->foreign('id_ue')
                  ->references('id_ue')
                  ->on('unidad_educativas')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('desafios', function (Blueprint $table) {
            // Es vital eliminar primero la clave foránea y luego la columna
            $table->dropForeign(['id_ue']);
            $table->dropColumn('id_ue');
        });
    }
};
