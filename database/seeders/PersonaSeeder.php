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
             'nombre' => 'Henry',
                'ape_paterno' => 'Coronel',
                'ape_materno' => null,
                'fecha_nac' => '1985-03-15',
                'ci' => '1234561',
                'celular' => 76543210,
                'ciudad' => 'La Paz',
                'zona' => 'Miraflores',
                'calle' => 'Av. Busch',
                'nro' => '123',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Franz',
                'ape_paterno' => 'Ciro',
                'ape_materno' => 'Valdez',
                'fecha_nac' => '1990-07-22',
                'ci' => '1234562',
                'celular' => 76543211,
                'ciudad' => 'Cochabamba',
                'zona' => 'Queru Queru',
                'calle' => 'Av. América',
                'nro' => '456',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Ronald',
                'ape_paterno' => 'Moya',
                'ape_materno' => null,
                'fecha_nac' => '1988-11-05',
                'ci' => '1234563',
                'celular' => 76543212,
                'ciudad' => 'Santa Cruz',
                'zona' => 'Equipetrol',
                'calle' => 'Calle Libertad',
                'nro' => '789',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
                'nombre' => 'Jhon',
                'ape_paterno' => 'Mamani',
                'ape_materno' => null,
                'fecha_nac' => '1995-02-10',
                'ci' => '1234564',
                'celular' => 76543213,
                'ciudad' => 'El Alto',
                'zona' => 'Ciudad Satélite',
                'calle' => 'Av. del Policía',
                'nro' => '22',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Juan Carlos',
                'ape_paterno' => 'Lima',
                'ape_materno' => null,
                'fecha_nac' => '1980-09-30',
                'ci' => '1234565',
                'celular' => 76543214,
                'ciudad' => 'La Paz',
                'zona' => 'Sopocachi',
                'calle' => 'Av. Ecuador',
                'nro' => '55',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Manuel',
                'ape_paterno' => 'Morante',
                'ape_materno' => null,
                'fecha_nac' => '1989-06-11',
                'ci' => '1234566',
                'celular' => 76543215,
                'ciudad' => 'Cochabamba',
                'zona' => 'Centro',
                'calle' => 'Junín',
                'nro' => '33',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Noel',
                'ape_paterno' => 'Larico',
                'ape_materno' => null,
                'fecha_nac' => '1992-01-25',
                'ci' => '1234567',
                'celular' => 76543216,
                'ciudad' => 'Oruro',
                'zona' => 'Zona Norte',
                'calle' => 'Sucre',
                'nro' => '12',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'David',
                'ape_paterno' => 'Gutierrez',
                'ape_materno' => null,
                'fecha_nac' => '1997-04-19',
                'ci' => '1234568',
                'celular' => 76543217,
                'ciudad' => 'Tarija',
                'zona' => 'San Roque',
                'calle' => 'Colon',
                'nro' => '8',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);
        
        DB::table('personas')->insert([
            'nombre' => 'Emmanuel',
                'ape_paterno' => 'Gutierrez',
                'ape_materno' => 'nuñez',
                'fecha_nac' => '1997-04-19',
                'ci' => '12345623',
                'celular' => 76543217,
                'ciudad' => 'Tarija',
                'zona' => 'San Roque',
                'calle' => 'Colon',
                'nro' => '8',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);

        DB::table('personas')->insert([
            'nombre' => 'Eddy',
                'ape_paterno' => 'Mamani',
                'ape_materno' => 'Condori',
                'fecha_nac' => '1997-04-19',
                'ci' => '1234568345',
                'celular' => 76543217,
                'ciudad' => 'Tarija',
                'zona' => 'San Roque',
                'calle' => 'Colon',
                'nro' => '8',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
        ]);
    }
}
