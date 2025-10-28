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
            'Muruamaya', 'San Martín', '27 de Mayo', 'Atipiris', 'Villa Remedios',
            'Tilata', 'Cupilupaca', 'Santiago de Machaca', 'Pathipi', 'Curahuara',
            'Bolivar', 'Viacha', 'Villa Adela', 'Cosmos', 'Maranatha',
            'Villa Aroma', 'El Valle', 'Senkata 79', 'Pacajes', 'Inquisivi',
            'Araca', 'Villa Juliana', 'La Hermosa', 'El Salvador', 'Fabril',
            'Patacamaya', 'El Porvenir', 'Mariscal', 'Las Fronteras', 'Lahuachaca',
            'Cosapa', 'Rosario', 'Cajuata', 'Vichaya', 'Charapaqui',
            '25 de Julio', 'Ventilla', 'Santa Rosa', 'Collana'
        ];

        $data = [];

        foreach ($distritos as $distrito) {
            $data[] = [
                'nombre' => $distrito,
                'nro_iglesias' => 0,
                'id_pastor' => null, // Puedes asignar si tienes id
                'id_grupo' => null,  // Puedes asignar si tienes id
                'sw_cambio' => false,
                'sw_estado' => false,
                'año' => '2025',
                'id_pastor' => 25,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('distritos')->insert($data);
    }
}
