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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id('id_grupo'); // PK personalizada
            $table->string('nombre');
            $table->integer('nro_distritos')->default(0);

            // FK hacia Administrativo
            $table->unsignedBigInteger('administrativo_id')->nullable();
            $table->foreign('administrativo_id')
                ->references('id_persona') // PK de Administrativo
                ->on('administrativos')
                ->onDelete('set null');
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo');
    }
};
