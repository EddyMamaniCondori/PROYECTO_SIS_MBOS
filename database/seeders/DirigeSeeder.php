<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <<<<< Esto es lo que falta
use Carbon\Carbon;

class DirigeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapeo de datos procesados: ID_DISTRITO => [ AÑO => ID_PASTOR ]
        // Basado en el cruce de tu imagen con tus listas de IDs.
        $asignaciones = [
            // 1: Muruamaya
            1 => [
                2023 => null, // No asignado en imagen
                2024 => 3,    // Emmanuel Aguilar Conde
                2025 => 3     // Emmanuel Aguilar Conde
            ],
            // 2: San Martín
            2 => [
                2023 => 9,    // John Chino Chipana
                2024 => 9,    // John Chino Chipana
                2025 => 4     // Fidel Aliaga Quispe
            ],
            // 3: 27 de Mayo
            3 => [
                2023 => 19,   // Omar Llanos Cohaila
                2024 => 19,   // Omar Llanos Cohaila
                2025 => 5     // Hugo Apaza Quiñones
            ],
            // 4: Atipiris
            4 => [
                2023 => 17,   // Juan Carlos Lima Huanca
                2024 => 6,    // Miguel Ángel Bautista Calle
                2025 => 6     // Miguel Ángel Bautista Calle
            ],
            // 5: Villa Remedios
            5 => [
                2023 => 8,    // Rubén Chambilla Esprella
                2024 => 8,    // Rubén Chambilla Esprella
                2025 => 7     // Wilmer Elio Chambi Catunta
            ],
            // 6: Tilata
            6 => [
                2023 => 16,   // Noel Larico Aruquipa
                2024 => 16,   // Noel Larico Aruquipa
                2025 => 8     // Rubén Chambilla Esprella
            ],
            // 7: Cupilupaca
            7 => [
                2023 => 4,    // Fidel Aliaga Quispe
                2024 => 4,    // Fidel Aliaga Quispe
                2025 => 9     // John Chino Chipana
            ],
            // 8: Santiago de Machaca
            8 => [
                2023 => 10,   // Mauro Chipana Serrano
                2024 => 10,   // Mauro Chipana Serrano
                2025 => 10    // Mauro Chipana Serrano
            ],
            // 9: Pathipi
            9 => [
                2023 => null, // Ángel Andrés Quispe Vera (FALTA ID EN TU LISTA)
                2024 => null, // Ángel Andrés Quispe Vera (FALTA ID EN TU LISTA)
                2025 => 11    // Robie Chuquimia Lucana
            ],
            // 10: Curahuara
            10 => [
                2023 => 5,    // Hugo Apaza Quiñones
                2024 => 5,    // Hugo Apaza Quiñones
                2025 => 12    // Alvaro Gabriel Condori Canaviri
            ],
            // 11: Bolivar
            11 => [
                2023 => 13,   // Henry Wilson Coronel Laime
                2024 => 13,   // Henry Wilson Coronel Laime
                2025 => 13    // Henry Wilson Coronel Laime
            ],
            // 12: Viacha
            12 => [
                2023 => 14,   // José Edwin Escarzo Nina
                2024 => 14,   // José Edwin Escarzo Nina
                2025 => 14    // José Edwin Escarzo Nina
            ],
            // 13: Villa Adela
            13 => [
                2023 => 15,   // David Gutierrez Limachi
                2024 => 15,   // David Gutierrez Limachi
                2025 => 15    // David Gutierrez Limachi
            ],
            // 14: Cosmos
            14 => [
                2023 => 32,   // Juan Eusebio Quispe Espinoza
                2024 => 24,   // Manuel Huascar Morante Kondori
                2025 => 16    // Noel Larico Aruquipa
            ],
            // 15: Maranatha
            15 => [
                2023 => null, // No asignado en imagen
                2024 => 17,   // Juan Carlos Lima Huanca
                2025 => 17    // Juan Carlos Lima Huanca
            ],
            // 16: Villa Aroma
            16 => [
                2023 => null, // No asignado en imagen
                2024 => null, // No asignado en imagen
                2025 => 18    // Adhemar Llanos Cohaila
            ],
            // 17: El Valle
            17 => [
                2023 => null, // Miguel Castro Vaca (FALTA ID EN TU LISTA)
                2024 => null, // Miguel Castro Vaca (FALTA ID EN TU LISTA)
                2025 => 19    // Omar Llanos Cohaila
            ],
            // 18: Senkata 79
            18 => [
                2023 => 25,   // Ronald Moya Condori
                2024 => 20,   // Edson Plinio Llusco Chavez
                2025 => 20    // Edson Plinio Llusco Chavez
            ],
            // 19: Pacajes
            19 => [
                2023 => 40,   // Franz Ciro Valdez Laura
                2024 => 21,   // Fidel John Mamani Limachi
                2025 => 21    // Fidel John Mamani Limachi
            ],
            // 20: Inquisivi
            20 => [
                2023 => 39,   // Percy Tarquiola Coria
                2024 => 7,    // Wilmer Elio Chambi Catunta
                2025 => 22    // Sergio Mamani Mamani
            ],
            // 21: Araca
            21 => [
                2023 => 28,   // Mario Carlos Pinto Salvatierra
                2024 => 23,   // Benjamín Josué Mena Laura
                2025 => 23    // Benjamín Josué Mena Laura
            ],
            // 22: Villa Juliana
            22 => [
                2023 => null, // No asignado
                2024 => null, // No asignado
                2025 => 24    // Manuel Huascar Morante Kondori
            ],
            // 23: La Hermosa
            23 => [
                2023 => 21,   // Fidel John Mamani Limachi
                2024 => 25,   // Ronald Moya Condori
                2025 => 25    // Ronald Moya Condori
            ],
            // 24: El Salvador
            24 => [
                2023 => 29,   // Mario Alejandro Pomacusi Cari
                2024 => 29,   // Mario Alejandro Pomacusi Cari
                2025 => 26    // Ever Pairo Mamani
            ],
            // 25: Fabril
            25 => [
                2023 => null, // Ronald Pedro Kapa Onofre (FALTA ID EN TU LISTA)
                2024 => null, // Carlos Jose Larico Aruquipa (FALTA ID EN TU LISTA)
                2025 => 27    // Mequías Pari Tipo
            ],
            // 26: Patacamaya
            26 => [
                2023 => 6,    // Miguel Ángel Bautista Calle
                2024 => 28,   // Mario Carlos Pinto Salvatierra
                2025 => 28    // Mario Carlos Pinto Salvatierra
            ],
            // 27: El Porvenir
            27 => [
                2023 => 37,   // Efrain Tarqui Chura
                2024 => 37,   // Efrain Tarqui Chura
                2025 => 29    // Mario Alejandro Pomacusi Cari
            ],
            // 28: Mariscal
            28 => [
                2023 => 27,   // Mequías Pari Tipo
                2024 => 27,   // Mequías Pari Tipo
                2025 => 30    // Juan Luis Quipildor Tito
            ],
            // 29: Las Fronteras
            29 => [
                2023 => null, // Norberto Marcelo Mamani (FALTA ID EN TU LISTA)
                2024 => 31,   // Josué Quispe Sánchez
                2025 => 31    // Josué Quispe Sánchez
            ],
            // 30: Lahuachaca
            30 => [
                2023 => 38,   // Isaac Tarqui Huanca
                2024 => 32,   // Juan Eusebio Quispe Espinoza
                2025 => 32    // Juan Eusebio Quispe Espinoza
            ],
            // 31: Cosapa
            31 => [
                2023 => 20,   // Edson Plinio Llusco Chavez
                2024 => 33,   // Nelo Quiuchaca Apaza
                2025 => 33    // Nelo Quiuchaca Apaza
            ],
            // 32: Rosario
            32 => [
                2023 => 18,   // Adhemar Llanos Cohaila
                2024 => null, // Ronald Pedro Kapa Onofre (FALTA ID EN TU LISTA)
                2025 => 34    // David Sajama Sánchez
            ],
            // 33: Cajuata
            33 => [
                2023 => 35,   // Juan Milton Sirpa Alcon
                2024 => 35,   // Juan Milton Sirpa Alcon
                2025 => 35    // Juan Milton Sirpa Alcon
            ],
            // 34: Vichaya
            34 => [
                2023 => 22,   // Sergio Mamani Mamani
                2024 => 22,   // Sergio Mamani Mamani
                2025 => 36    // Nelson Tapia Muñoz
            ],
            // 35: Charapaqui
            35 => [
                2023 => 30,   // Juan Luis Quipildor Tito
                2024 => 30,   // Juan Luis Quipildor Tito
                2025 => 37    // Efrain Tarqui Chura
            ],
            // 36: 25 de julio
            36 => [
                2023 => 24,   // Manuel Huascar Morante Kondori
                2024 => 38,   // Isaac Tarqui Huanca
                2025 => 38    // Isaac Tarqui Huanca
            ],
            // 37: Ventilla
            37 => [
                2023 => null, // No asignado en imagen
                2024 => 39,   // Percy Tarquiola Coria
                2025 => 39    // Percy Tarquiola Coria
            ],
            // 38: Santa Rosa
            38 => [
                2023 => 3,    // Emmanuel Aguilar Conde
                2024 => 40,   // Franz Ciro Valdez Laura
                2025 => 40    // Franz Ciro Valdez Laura
            ],
            // 39: Collana
            39 => [
                2023 => 33,   // Nelo Quiuchaca Apaza
                2024 => 41,   // Absalón Eleazar Valeriano Condori
                2025 => 41    // Absalón Eleazar Valeriano Condori
            ],
        ];

        $registros = [];
        $fechaActual = now();

        foreach ($asignaciones as $idDistrito => $anios) {
            foreach ($anios as $anio => $idPastor) {
                // Solo insertamos si hay un ID de pastor válido (no nulo)
                if ($idPastor !== null) {
                    $registros[] = [
                        'id_distrito' => $idDistrito,
                        'id_pastor' => $idPastor,
                        'fecha_asignacion' => Carbon::create($anio, 1, 1),   // 1 de Enero
                        'fecha_finalizacion' => Carbon::create($anio, 12, 31), // 31 de Diciembre
                        'año' => $anio,
                        'created_at' => $fechaActual,
                        'updated_at' => $fechaActual,
                    ];
                }
            }
        }

        // Insertar en lotes para eficiencia
        // Asegúrate de que tu tabla se llame 'historicos', si no, cámbialo aquí.
        DB::table('diriges')->insert($registros);
    }
}
