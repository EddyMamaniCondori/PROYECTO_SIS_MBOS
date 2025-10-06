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
        Schema::create('desafio_mensuales', function (Blueprint $table) {
            $table->id('id_desafio'); // PK
            $table->string('mes'); // Ej: Enero, Febrero
            $table->integer('anio');
            $table->integer('desafio_visitacion')->default(0);
            $table->integer('desafio_bautiso')->default(0);
            $table->integer('desafio_inst_biblicos')->default(0);
            $table->integer('desafios_est_biblicos')->default(0);
            $table->integer('visitas_alcanzadas')->default(0);
            $table->integer('bautisos_alcanzados')->default(0);
            $table->integer('instructores_alcanzados')->default(0);
            $table->integer('estudiantes_alcanzados')->default(0);

            // Relación con Iglesia
            $table->unsignedBigInteger('iglesia_id');
            $table->foreign('iglesia_id')
                  ->references('id_iglesia')
                  ->on('iglesias')
                  ->onDelete('cascade');

            // Relación con Pastor
            $table->unsignedBigInteger('pastor_id');
            $table->foreign('pastor_id')
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
        Schema::dropIfExists('desafio_mensuale');
    }
};
