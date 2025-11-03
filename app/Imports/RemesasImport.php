<?php

// app/Imports/RemesasImport.php
namespace App\Imports;

use App\Models\RemesaExcel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RemesasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new RemesaExcel([
            'codigo' => $row['codigo'],
            'nombre' => $row['nombre'],
            'valor_1' => $row['valor_01'] ?? 0,
            'mes_1' => $row['month_display01'] ?? null,
            'valor_2' => $row['valor_02'] ?? 0,
            'mes_2' => $row['month_display02'] ?? null,
            'valor_3' => $row['valor_03'] ?? 0,
            'mes_3' => $row['month_display03'] ?? null,
            'valor_4' => $row['valor_04'] ?? 0,
            'mes_4' => $row['month_display04'] ?? null,
            'valor_5' => $row['valor_05'] ?? 0,
            'mes_5' => $row['month_display05'] ?? null,
            'valor_6' => $row['valor_06'] ?? 0,
            'mes_6' => $row['month_display06'] ?? null,
            'valor_7' => $row['valor_07'] ?? 0,
            'mes_7' => $row['month_display07'] ?? null,
            'valor_8' => $row['valor_08'] ?? 0,
            'mes_8' => $row['month_display08'] ?? null,
            'valor_9' => $row['valor_09'] ?? 0,
            'mes_9' => $row['month_display09'] ?? null,
            'valor_10' => $row['valor_10'] ?? 0,
            'mes_10' => $row['month_display10'] ?? null,
            'valor_11' => $row['valor_11'] ?? 0,
            'mes_11' => $row['month_display11'] ?? null,
            'valor_12' => $row['valor_12'] ?? 0,
            'mes_12' => $row['month_display12'] ?? null,
            'total' => $row['total'] ?? 0,
        ]);
    }
}

