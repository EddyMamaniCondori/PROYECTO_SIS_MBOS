<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use App\Models\Grupo; // <<<< Importa el modelo Grupo
use Carbon\Carbon;
use App\Models\Administrativo;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Obtener los administrativos existentes (los 3 últimos de PersonaSeeder)
        $administrativos = Administrativo::all();

        // Verificar que existan al menos 3 administrativos
        if ($administrativos->count() < 3) {
            $this->command->error('No hay suficientes administrativos para asignar a los grupos.');
            return;
        }

        // Crear los 3 grupos con un administrativo asignado
        $grupos = [
            ['nombre' => 'GP1', 'nro_distritos' => 0],
            ['nombre' => 'GP2', 'nro_distritos' => 0],
            ['nombre' => 'GP3', 'nro_distritos' => 0],
        ];

        foreach ($grupos as $index => $data) {
            Grupo::create([
                'nombre' => $data['nombre'],
                'nro_distritos' => $data['nro_distritos'],
                'administrativo_id' => $administrativos[$index]->id_persona, // asigna 1 administrativo a cada grupo
            ]);
        }
    }
}
