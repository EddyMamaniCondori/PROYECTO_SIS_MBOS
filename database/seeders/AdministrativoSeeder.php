<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class AdministrativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $administrativos = [
            [
                'id_persona' => 1,
                'cargo' => 'Secretario General',
                'ministerio' => 'Administración Central',
            ],
            [
                'id_persona' => 2,
                'cargo' => 'Tesorero',
                'ministerio' => 'Finanzas y Recursos',
            ],
        ];

        foreach ($administrativos as $admin) {
            DB::table('administrativos')->insert([
                'id_persona' => $admin['id_persona'],
                'cargo' => $admin['cargo'],
                'ministerio' => $admin['ministerio'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
