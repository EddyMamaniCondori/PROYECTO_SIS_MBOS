<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class DesafioMensualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $iglesia_id = 1; // ID de la iglesia
        $pastor_id = 1;  // ID del pastor
        $anio = 2025;

        // Enero
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Enero',
            'anio' => $anio,
            'desafio_visitacion' => 20,
            'desafio_bautiso' => 5,
            'desafio_inst_biblicos' => 3,
            'desafios_est_biblicos' => 10,
            'visitas_alcanzadas' => 15,
            'bautisos_alcanzados' => 2,
            'instructores_alcanzados' => 1,
            'estudiantes_alcanzados' => 8,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Febrero
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Febrero',
            'anio' => $anio,
            'desafio_visitacion' => 18,
            'desafio_bautiso' => 4,
            'desafio_inst_biblicos' => 2,
            'desafios_est_biblicos' => 9,
            'visitas_alcanzadas' => 12,
            'bautisos_alcanzados' => 3,
            'instructores_alcanzados' => 1,
            'estudiantes_alcanzados' => 7,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Marzo
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Marzo',
            'anio' => $anio,
            'desafio_visitacion' => 22,
            'desafio_bautiso' => 6,
            'desafio_inst_biblicos' => 3,
            'desafios_est_biblicos' => 11,
            'visitas_alcanzadas' => 18,
            'bautisos_alcanzados' => 4,
            'instructores_alcanzados' => 2,
            'estudiantes_alcanzados' => 9,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Abril
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Abril',
            'anio' => $anio,
            'desafio_visitacion' => 25,
            'desafio_bautiso' => 5,
            'desafio_inst_biblicos' => 4,
            'desafios_est_biblicos' => 12,
            'visitas_alcanzadas' => 20,
            'bautisos_alcanzados' => 5,
            'instructores_alcanzados' => 2,
            'estudiantes_alcanzados' => 10,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mayo
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Mayo',
            'anio' => $anio,
            'desafio_visitacion' => 28,
            'desafio_bautiso' => 7,
            'desafio_inst_biblicos' => 5,
            'desafios_est_biblicos' => 14,
            'visitas_alcanzadas' => 22,
            'bautisos_alcanzados' => 6,
            'instructores_alcanzados' => 3,
            'estudiantes_alcanzados' => 12,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Junio
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Junio',
            'anio' => $anio,
            'desafio_visitacion' => 26,
            'desafio_bautiso' => 6,
            'desafio_inst_biblicos' => 4,
            'desafios_est_biblicos' => 13,
            'visitas_alcanzadas' => 21,
            'bautisos_alcanzados' => 5,
            'instructores_alcanzados' => 2,
            'estudiantes_alcanzados' => 11,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Julio
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Julio',
            'anio' => $anio,
            'desafio_visitacion' => 27,
            'desafio_bautiso' => 5,
            'desafio_inst_biblicos' => 4,
            'desafios_est_biblicos' => 12,
            'visitas_alcanzadas' => 19,
            'bautisos_alcanzados' => 4,
            'instructores_alcanzados' => 2,
            'estudiantes_alcanzados' => 10,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Agosto
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Agosto',
            'anio' => $anio,
            'desafio_visitacion' => 30,
            'desafio_bautiso' => 6,
            'desafio_inst_biblicos' => 5,
            'desafios_est_biblicos' => 13,
            'visitas_alcanzadas' => 24,
            'bautisos_alcanzados' => 5,
            'instructores_alcanzados' => 3,
            'estudiantes_alcanzados' => 12,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Septiembre
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Septiembre',
            'anio' => $anio,
            'desafio_visitacion' => 25,
            'desafio_bautiso' => 4,
            'desafio_inst_biblicos' => 3,
            'desafios_est_biblicos' => 11,
            'visitas_alcanzadas' => 20,
            'bautisos_alcanzados' => 3,
            'instructores_alcanzados' => 2,
            'estudiantes_alcanzados' => 9,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Octubre
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Octubre',
            'anio' => $anio,
            'desafio_visitacion' => 28,
            'desafio_bautiso' => 5,
            'desafio_inst_biblicos' => 4,
            'desafios_est_biblicos' => 12,
            'visitas_alcanzadas' => 22,
            'bautisos_alcanzados' => 4,
            'instructores_alcanzados' => 2,
            'estudiantes_alcanzados' => 10,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Noviembre
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Noviembre',
            'anio' => $anio,
            'desafio_visitacion' => 26,
            'desafio_bautiso' => 6,
            'desafio_inst_biblicos' => 3,
            'desafios_est_biblicos' => 11,
            'visitas_alcanzadas' => 21,
            'bautisos_alcanzados' => 5,
            'instructores_alcanzados' => 2,
            'estudiantes_alcanzados' => 10,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Diciembre
        DB::table('desafio_mensuales')->insert([
            'mes' => 'Diciembre',
            'anio' => $anio,
            'desafio_visitacion' => 26,
            'desafio_bautiso' => 6,
            'desafio_inst_biblicos' => 3,
            'desafios_est_biblicos' => 11,
            'visitas_alcanzadas' => 21,
            'bautisos_alcanzados' => 5,
            'instructores_alcanzados' => 2,
            'estudiantes_alcanzados' => 10,
            'iglesia_id' => $iglesia_id,
            'pastor_id' => $pastor_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
