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
        Schema::table('distritos', function (Blueprint $table) {

            $table->foreignId('id_responsable_remesa')
                      ->nullable() 
                      ->constrained('personales', 'id_personal')
                      ->onUpdate('cascade')
                      ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distritos', function (Blueprint $table) {
            // Es importante eliminar primero la llave foránea y luego la columna
            $table->dropForeign(['id_responsable_remesa']);
            $table->dropColumn('id_responsable_remesa');
        });
    }
};
