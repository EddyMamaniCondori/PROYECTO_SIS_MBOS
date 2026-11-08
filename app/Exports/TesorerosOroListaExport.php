<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TesorerosOroListaExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected $tesoreros;

    // A la vista de Solo Lista solo le importan los tesoreros, no necesitamos los meses
    public function __construct($tesoreros)
    {
        $this->tesoreros = $tesoreros;
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Cod',
            'Iglesia',
            'Distrito',
            'Lugar',
            'Tipo',
            'Categoría',
            'Recibido' // Una única columna de control al final
        ];
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->tesoreros as $index => $t) {
            $data[] = [
                $index + 1,
                $t->codigo_iglesia,
                $t->nombre_iglesia,
                $t->nombre_distrito,
                $t->lugar,
                $t->tipo_iglesia,
                $t->categoria,
                '' // Celda vacía en Excel para la columna "Recibido"
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Fila 1 (Cabeceras): Negrita, Letra blanca, Fondo Azul
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '0D47A1']]
            ],
        ];
    }
}
