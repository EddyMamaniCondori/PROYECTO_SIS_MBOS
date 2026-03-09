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
        Schema::table('unidad_educativas', function (Blueprint $table) {
            // unsignedInteger asegura que solo sean números positivos (0 a 4,294,967,295)
            $table->unsignedInteger('desafios_bautismos')->default(0)->after('id_ue'); 
            $table->unsignedInteger('bautismos_alcanzados')->default(0)->after('desafios_bautismos');

            // 2. Agregamos las columnas para las llaves foráneas
            // Usamos unsignedBigInteger si id_personal es la clave primaria estándar de Laravel
            $table->unsignedBigInteger('id_director')->nullable()->after('bautismos_alcanzados');
            $table->unsignedBigInteger('id_secretaria')->nullable()->after('id_director');

            // 3. Definimos las restricciones de llave foránea
            $table->foreign('id_director')
                ->references('id_personal')
                ->on('personales')
                ->onDelete('set null'); // Si se borra el personal, el campo queda vacío

            $table->foreign('id_secretaria')
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
       Schema::table('unidad_educativas', function (Blueprint $table) {
            $table->dropColumn(['desafios_bautismos', 'bautismos_alcanzados']);
        });
    }
};
