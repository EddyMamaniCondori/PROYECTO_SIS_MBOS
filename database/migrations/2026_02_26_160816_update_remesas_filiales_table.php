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
            $table->decimal('gasto', 10, 3)->default(0)->after('monto');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remesas_filiales', function (Blueprint $table) {
            $table->dropColumn('gasto');
        });
    }
};
