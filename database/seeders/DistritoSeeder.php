<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class DistritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distritos = [
            ['nombre' => 'Muruamaya', 'id_pastor' => 3],
            ['nombre' => 'San Martín', 'id_pastor' => 4],
            ['nombre' => '27 de Mayo', 'id_pastor' => 5],
            ['nombre' => 'Atipiris', 'id_pastor' => 6],
            ['nombre' => 'Villa Remedios', 'id_pastor' => 7],
            ['nombre' => 'Tilata', 'id_pastor' => 8],
            ['nombre' => 'Cupilupaca', 'id_pastor' => 9],
            ['nombre' => 'Santiago de Machaca', 'id_pastor' => 10],
            ['nombre' => 'Pathipi', 'id_pastor' => 11],
            ['nombre' => 'Curahuara', 'id_pastor' => 12],
            ['nombre' => 'Bolivar', 'id_pastor' => 13],
            ['nombre' => 'Viacha', 'id_pastor' => 14],
            ['nombre' => 'Villa Adela', 'id_pastor' => 15],
            ['nombre' => 'Cosmos', 'id_pastor' => 16],
            ['nombre' => 'Maranatha', 'id_pastor' => 17],
            ['nombre' => 'Villa Aroma', 'id_pastor' => 18],
            ['nombre' => 'El Valle', 'id_pastor' => 19],
            ['nombre' => 'Senkata 79', 'id_pastor' => 20],
            ['nombre' => 'Pacajes', 'id_pastor' => 21],
            ['nombre' => 'Inquisivi', 'id_pastor' => 22],
            ['nombre' => 'Araca', 'id_pastor' => 23],
            ['nombre' => 'Villa Juliana', 'id_pastor' => 24],
            ['nombre' => 'La Hermosa', 'id_pastor' => 25],
            ['nombre' => 'El Salvador', 'id_pastor' => 26],
            ['nombre' => 'Fabril', 'id_pastor' => 27],
            ['nombre' => 'Patacamaya', 'id_pastor' => 28],
            ['nombre' => 'El Porvenir', 'id_pastor' => 29],
            ['nombre' => 'Mariscal', 'id_pastor' => 30],
            ['nombre' => 'Las Fronteras', 'id_pastor' => 31],
            ['nombre' => 'Lahuachaca', 'id_pastor' => 32],
            ['nombre' => 'Cosapa', 'id_pastor' => 33],
            ['nombre' => 'Rosario', 'id_pastor' => 34],
            ['nombre' => 'Cajuata', 'id_pastor' => 35],
            ['nombre' => 'Vichaya', 'id_pastor' => 36],
            ['nombre' => 'Charapaqui', 'id_pastor' => 37],
            ['nombre' => '25 de Julio', 'id_pastor' => 38],
            ['nombre' => 'Ventilla', 'id_pastor' => 39],
            ['nombre' => 'Santa Rosa', 'id_pastor' => 40],
            ['nombre' => 'Collana', 'id_pastor' => 41],
        ];

        $data = [];

        foreach ($distritos as $distrito) {
            $data[] = [
                'nombre' => $distrito['nombre'],
                'nro_iglesias' => 0,
                'estado' => true, // eliminación lógica
                'id_pastor' => $distrito['id_pastor'],
                'id_grupo' => null,  // Puedes asignar si tienes id
                'sw_cambio' => false,
                'año' => '2025',
                'fecha_asignacion' => '01/01/2025',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('distritos')->insert($data);
    }
}
