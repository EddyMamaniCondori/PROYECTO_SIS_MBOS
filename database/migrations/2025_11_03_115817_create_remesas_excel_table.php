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
        // database/migrations/xxxx_xx_xx_create_remesas_excel_table.php
        Schema::create('remesas_excel', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo');
            $table->string('nombre');
            for ($i = 1; $i <= 12; $i++) {
                $table->decimal("valor_$i", 12, 2)->nullable();
                $table->string("mes_$i")->nullable();
            }
            $table->decimal('total', 14, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remesas_excel');
    }
};
