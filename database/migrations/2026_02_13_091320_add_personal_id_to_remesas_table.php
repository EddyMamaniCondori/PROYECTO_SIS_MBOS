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
            // 1. Creamos la columna (debe ser del mismo tipo que id_personal)
            // Usualmente id_personal es un Big Integer sin signo.
            $table->unsignedBigInteger('id_personal')->nullable()->after('id');

            // 2. Definimos la relación foránea explícitamente
            $table->foreign('id_personal')
                  ->references('id_personal') 
                  ->on('personales')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remesas', function (Blueprint $table) {
            
        });
    }
};
