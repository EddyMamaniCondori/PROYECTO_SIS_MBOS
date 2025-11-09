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
        Schema::create('bautisos', function (Blueprint $table) {
            $table->id('id_bautiso');
            $table->enum('tipo', ['bautizo', 'profesion de fe', 'rebautismo']);
            $table->date('fecha_bautizo')->nullable();
            

            $table->unsignedBigInteger('id_iglesia');
            $table->foreign('id_iglesia')
                  ->references('id_iglesia')
                  ->on('iglesias')
                  ->onDelete('cascade');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bautisos');
    }
};
