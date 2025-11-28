<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FilialesMensualExport implements FromArray, WithHeadings
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Iglesia',
            'Tipo',
            'Distrito',

            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre',

            'Total Anual'
        ];
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->rows as $r) {
            $data[] = [
                $r->codigo,
                $r->nombre,
                $r->tipo,
                $r->distrito,

                $r->enero,
                $r->febrero,
                $r->marzo,
                $r->abril,
                $r->mayo,
                $r->junio,
                $r->julio,
                $r->agosto,
                $r->septiembre,
                $r->octubre,
                $r->noviembre,
                $r->diciembre,

                $r->total_anual
            ];
        }

        return $data;
    }
}
