<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DistritalDirectExport implements FromArray, WithHeadings
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function headings(): array
    {
        return [
            'Distrito',
            'Desafío',
            'Total Anual',
            'Diferencia'
        ];
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->rows as $r) {
            $data[] = [
                $r->nombre_distrito,
                $r->blanco_monto,
                $r->total_anual,
                $r->total_anual - $r->blanco_monto,
            ];
        }

        return $data;
    }
}


