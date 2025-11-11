// database/migrations/YYYY_MM_DD_add_auth_fields_to_personas.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Campos de autenticación
            $table->string('email')->unique()->nullable()->after('ci');
            $table->string('password')->nullable()->after('email');
            $table->rememberToken()->after('password');
            $table->timestamp('email_verified_at')->nullable()->after('password');
            
            // Campo de estado (opcional pero recomendado)
            if (!Schema::hasColumn('personas', 'estado')) {
                $table->boolean('estado')->default(true)->after('email_verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn(['email', 'password', 'remember_token', 'email_verified_at']);
        });
    }
};