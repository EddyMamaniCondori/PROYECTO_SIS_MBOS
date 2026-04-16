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
        Schema::table('remesas_filiales', function (Blueprint $table) {
            $table->decimal('fondo_l_anterior', 15, 2)->default(0)->after('pro_templo');
            $table->decimal('fondo_l_final', 15, 2)->default(0)->after('gasto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remesas_filiales', function (Blueprint $table) {
            $table->dropColumn(['fondo_l_anterior', 'fondo_l_final']);
        });
    }
};
