<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('personas')->insert([
            'nombre' => 'Juan',
            'ape_paterno' => 'Perez',
            'ape_materno' => 'Lopez',
            'fecha_nac' => '1990-04-15',
            'ci' => '1000001',
            'edad' => 35,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Maria',
            'ape_paterno' => 'Gonzales',
            'ape_materno' => 'Mamani',
            'fecha_nac' => '1995-08-22',
            'ci' => '1000002',
            'edad' => 30,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Luis',
            'ape_paterno' => 'Quispe',
            'ape_materno' => 'Choque',
            'fecha_nac' => '1988-03-10',
            'ci' => '1000003',
            'edad' => 37,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Carla',
            'ape_paterno' => 'Rojas',
            'ape_materno' => 'Flores',
            'fecha_nac' => '1992-07-05',
            'ci' => '1000004',
            'edad' => 33,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Pedro',
            'ape_paterno' => 'Mendoza',
            'ape_materno' => 'Luna',
            'fecha_nac' => '1985-11-12',
            'ci' => '1000005',
            'edad' => 39,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Sofia',
            'ape_paterno' => 'Vargas',
            'ape_materno' => 'Calle',
            'fecha_nac' => '1998-02-27',
            'ci' => '1000006',
            'edad' => 27,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Andres',
            'ape_paterno' => 'Ramirez',
            'ape_materno' => 'Gutierrez',
            'fecha_nac' => '1993-09-09',
            'ci' => '1000007',
            'edad' => 32,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Lucia',
            'ape_paterno' => 'Torrez',
            'ape_materno' => 'Pinto',
            'fecha_nac' => '2000-01-15',
            'ci' => '1000008',
            'edad' => 25,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Diego',
            'ape_paterno' => 'Lopez',
            'ape_materno' => 'Sanchez',
            'fecha_nac' => '1989-06-30',
            'ci' => '1000009',
            'edad' => 36,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Ana',
            'ape_paterno' => 'Fernandez',
            'ape_materno' => 'Aramayo',
            'fecha_nac' => '1997-12-01',
            'ci' => '1000010',
            'edad' => 27,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
