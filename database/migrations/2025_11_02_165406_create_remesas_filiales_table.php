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
        Schema::create('remesas_filiales', function (Blueprint $table) {
              $table->unsignedBigInteger('id_remesa')->primary();
                $table->decimal('ofrenda', 10, 3)->default(0);
                $table->decimal('diezmo', 10, 3)->default(0);
                $table->decimal('pro_templo', 10, 3)->default(0);
                $table->decimal('fondo_local', 10, 3)->default(0);
                $table->decimal('monto_remesa', 10, 3)->default(0);
                $table->timestamps();
                // Clave foránea heredada
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
        Schema::dropIfExists('remesas_filiales');
    }
};
