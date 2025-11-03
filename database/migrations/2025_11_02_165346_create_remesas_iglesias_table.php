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
        Schema::create('remesas_iglesias', function (Blueprint $table) {
            $table->unsignedBigInteger('id_remesa')->primary();

            $table->decimal('monto', 10, 3);

            // Clave foránea que hereda la PK de remesas
            $table->foreign('id_remesa')
                ->references('id_remesa')
                ->on('remesas')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remesas_iglesias');
    }
};
