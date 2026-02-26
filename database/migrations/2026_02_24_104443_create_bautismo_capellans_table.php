<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bautismo_capellans', function (Blueprint $table) {
            $table->id('id_bautiso');
            $table->enum('tipo', ['bautizo', 'profesion de fe', 'rebautismo']);
            $table->date('fecha_bautizo')->nullable();
            $table->unsignedBigInteger('id_ue');
            $table->foreign('id_ue')
                  ->references('id_ue')
                  ->on('unidad_educativas')
                  ->onDelete('cascade');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bautismo_capellans');
    }
};
