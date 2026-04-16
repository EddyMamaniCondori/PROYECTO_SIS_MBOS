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
        Schema::create('remesas_semanales', function (Blueprint $table) {
            $table->unsignedBigInteger('id_remesa'); 
            $table->integer('nro_semana'); // 1 al 5

            // 2. Definimos la LLAVE PRIMARIA COMPUESTA
            // Esto permite que exista (18405, 1), (18405, 2), etc.
            $table->primary(['id_remesa', 'nro_semana']);
            // Totales de la semana (Consolidados)
            $table->decimal('diezmo_total', 10, 2)->default(0);
            $table->decimal('ofrenda_total', 10, 2)->default(0);
            $table->decimal('pacto_total', 10, 2)->default(0);
            $table->decimal('especiales_total', 10, 2)->default(0); // Primicia, Gratitud, etc.
            $table->decimal('pro_templo_total', 10, 2)->default(0);

            // El "Excel" dinámico en JSONB
            $table->jsonb('detalle_filas')->nullable(); 

            $table->timestamps();

            // Conexión con la tabla remesas
            $table->foreign('id_remesa')
                ->references('id_remesa')
                ->on('remesas')
                ->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remesas_semanales');
    }
};
