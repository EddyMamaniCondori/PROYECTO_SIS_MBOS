<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class IglesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Iglesia 1
        DB::table('iglesias')->insert([
            'nombre' => 'Santa Rosa',
            'feligresia' => 120,
            'feligrasia_asistente' => 80,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 1',
            'calle' => 'Calle 1',
            'nro' => '101',
            'lugar' => 'ALTIPLANO',
            'tipo' => 'FILIAL',
            'distrito_id' => 1, // Distrito 1
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 2
        DB::table('iglesias')->insert([
            'nombre' => 'Rosas Pampa',
            'feligresia' => 150,
            'feligrasia_asistente' => 90,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 2',
            'calle' => 'Calle 2',
            'nro' => '102',
            'lugar' => 'EL ALTO',
            'tipo' => 'IGLESIA',
            'distrito_id' => 2, // Distrito 2
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 3
        DB::table('iglesias')->insert([
            'nombre' => 'Santiago II',
            'feligresia' => 200,
            'feligrasia_asistente' => 120,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 3',
            'calle' => 'Calle 3',
            'nro' => '103',
            'lugar' => 'EL ALTO',
            'tipo' => 'GRUPO',
            'distrito_id' => 3, // Distrito 3
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 4
        DB::table('iglesias')->insert([
            'nombre' => 'Minero',
            'feligresia' => 180,
            'feligrasia_asistente' => 100,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 4',
            'calle' => 'Calle 4',
            'nro' => '104',
            'lugar' => 'ALTIPLANO',
            'tipo' => 'FILIAL',
            'distrito_id' => 4, // Distrito 4
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 5
        DB::table('iglesias')->insert([
            'nombre' => 'Avaroa',
            'feligresia' => 140,
            'feligrasia_asistente' => 90,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 5',
            'calle' => 'Calle 5',
            'nro' => '105',
            'lugar' => 'EL ALTO',
            'tipo' => 'FILIAL',
            'distrito_id' => 5, // Distrito 5
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 6
        DB::table('iglesias')->insert([
            'nombre' => 'Bolivar Municipal',
            'feligresia' => 160,
            'feligrasia_asistente' => 110,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 6',
            'calle' => 'Calle 6',
            'nro' => '106',
            'lugar' => 'ALTIPLANO',
            'tipo' => 'FILIAL',
            'distrito_id' => 6, // Distrito 6
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 7
        DB::table('iglesias')->insert([
            'nombre' => 'Villa Bolivar D',
            'feligresia' => 130,
            'feligrasia_asistente' => 85,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 7',
            'calle' => 'Calle 7',
            'nro' => '107',
            'lugar' => 'EL ALTO',
            'tipo' => 'FILIAL',
            'distrito_id' => 7, // Distrito 7
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 8
        DB::table('iglesias')->insert([
            'nombre' => 'Horizontes II',
            'feligresia' => 150,
            'feligrasia_asistente' => 95,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 8',
            'calle' => 'Calle 8',
            'nro' => '108',
            'lugar' => 'EL ALTO',
            'tipo' => 'FILIAL',
            'distrito_id' => 1, // Vuelve a Distrito 1
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 9
        DB::table('iglesias')->insert([
            'nombre' => 'Horizontes III',
            'feligresia' => 170,
            'feligrasia_asistente' => 100,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 9',
            'calle' => 'Calle 9',
            'nro' => '109',
            'lugar' => 'ALTIPLANO',
            'tipo' => 'FILIAL',
            'distrito_id' => 2, // Distrito 2
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 10
        DB::table('iglesias')->insert([
            'nombre' => 'La Hermosa',
            'feligresia' => 190,
            'feligrasia_asistente' => 120,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 10',
            'calle' => 'Calle 10',
            'nro' => '110',
            'lugar' => 'EL ALTO',
            'tipo' => 'IGLESIA',
            'distrito_id' => 3, // Distrito 3
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Iglesia 11
        DB::table('iglesias')->insert([
            'nombre' => 'Villa Concepcion',
            'feligresia' => 180,
            'feligrasia_asistente' => 110,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 11',
            'calle' => 'Calle 11',
            'nro' => '111',
            'lugar' => 'ALTIPLANO',
            'tipo' => 'IGLESIA',
            'distrito_id' => 4, // Distrito 4
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('iglesias')->insert([
            'nombre' => 'Maranatha',
            'feligresia' => 180,
            'feligrasia_asistente' => 110,
            'ciudad' => 'Ciudad Ejemplo',
            'zona' => 'Zona 11',
            'calle' => 'Calle 11',
            'nro' => '111',
            'lugar' => 'ALTIPLANO',
            'tipo' => 'GRUPO',
            'distrito_id' => 4, // Distrito 4
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
