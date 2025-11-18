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
        Schema::create('lideres_local', function (Blueprint $table) {
            $table->id('id_lideres'); // Usamos 'id_' como lo solicitaste
            $table->unsignedBigInteger('id_iglesia')->unique(); 
            $table->enum('tipo', ['Filial', 'Grupo', 'Iglesia']);
            $table->integer('Dir_Filial')->default(0);
            $table->integer('Dir_congregacion')->default(0);
            $table->integer('Anciano')->default(0);
            $table->integer('Diaconisas')->default(0);
            $table->integer('Diaconos')->default(0);
            $table->integer('EESS_Adultos')->default(0);
            $table->integer('EESS_Jovenes')->default(0);
            $table->integer('EESS_Niños')->default(0);
            $table->integer('GP')->default(0);
            $table->integer('Parejas_misioneras')->default(0);
            $table->timestamps();
            // Definición de la Clave Foránea
            $table->foreign('id_iglesia')
                  ->references('id_iglesia') // Asume que la PK de iglesias es 'id_iglesia'
                  ->on('iglesias')
                  ->onDelete('cascade'); // Si se elimina la iglesia, se elimina este registro
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lideres_local');
    }
};
