<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;
class RemesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // 1. Definimos las fechas para ESTA única remesa
    // Ejemplo: Límite el 20 de Enero de 2025
        $fechaLimite = Carbon::create(2022, 12, 20);
        
        // Ejemplo: Se entregó el 18 de Enero (2 días antes)
        $fechaEntrega = Carbon::create(2022, 12, 18);

        // 2. Calculamos la diferencia y el estado (tu misma lógica)
        $diferencia = $fechaEntrega->diffInDays($fechaLimite, false);

        if ($diferencia >= 0) {
            $estado = "Sin atraso ({$diferencia} días antes)";
        } else {
            $estado = "Con retraso (" . abs($diferencia) . " días)";
        }

        // 3. Insertamos EL registro único
        DB::table('remesas')->insert([
            'cierre'        => true,  // O false, según quieras probar
            'deposito'      => true,
            'documentacion' => true,
            'fecha_entrega' => $fechaEntrega->toDateString(),
            'fecha_limite'  => $fechaLimite->toDateString(),
            'estado'        => $estado,
            'observacion'   => "Remesa única de prueba Enero",
            'created_at'    => now(), // Importante para mantener el registro de creación
            'updated_at'    => now(),
        ]);
    }
}
