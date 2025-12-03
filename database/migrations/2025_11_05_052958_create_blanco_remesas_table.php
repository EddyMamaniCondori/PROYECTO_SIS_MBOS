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
        Schema::create('blanco_remesas', function (Blueprint $table) {
            $table->id('id_blanco');
            $table->decimal('monto', 10, 2)->default(0);
            $table->decimal('alcanzado', 10, 2)->default(0);
            $table->decimal('diferencia', 10, 2)->default(0);
            $table->integer('anio');

            // Relaciones
            $table->unsignedBigInteger('id_distrito')->nullable();;
            $table->unsignedBigInteger('id_pastor')->nullable();;

            // Claves foráneas
            $table->foreign('id_distrito')
                ->references('id_distrito')
                ->on('distritos')
                ->onDelete('cascade');

            $table->foreign('id_pastor')->default(0)
                ->references('id_pastor')
                ->on('pastors')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blanco_remesas');
    }
};
