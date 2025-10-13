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
        Schema::create('distritos_temporal', function (Blueprint $table) {
            $table->id('id_distrito');
            $table->string('nombre', 150);
            $table->integer('nro_iglesias'); 
            $table->unsignedBigInteger('id_pastor');
            $table->unsignedBigInteger('id_grupo');
            $table->boolean('estado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distritos_temporal');
    }
};
