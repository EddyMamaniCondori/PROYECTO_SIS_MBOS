<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendientesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $datos;
    protected $periodos;
    protected $mesesNom;

    public function __construct($datos , $periodos, $mesesNom)
    {
        $this->datos = $datos;
        $this->periodos = $periodos;
        $this->mesesNom = $mesesNom;
    }

    public function collection()
    {
        return $this->datos->flatMap(function($iglesias) {
            return $iglesias;
        });
    }

    public function headings(): array
    {
        // Columnas fijas
        $cabecera = ['Distrito', 'Código', 'Iglesia', 'Tipo', 'Pastor', 'Responsable'];

        // Columnas dinámicas de meses (Aquí es donde fallaba antes)
        foreach ($this->periodos as $p) {
            $parts = explode('-', $p);
            $m = (int)$parts[1];
            $anio = $parts[0];
            $cabecera[] = ($this->mesesNom[$m] ?? $m) . '-' . $anio;
        }

        return $cabecera;
    }


    public function map($iglesia): array
    {
        // Datos básicos de la fila
        $fila = [
            $iglesia->distrito,
            $iglesia->codigo,
            $iglesia->nombre,
            $iglesia->tipo,
            $iglesia->nombre_pas,
            $iglesia->nombre_res,
        ];

        // Lógica para llenar los estados en las columnas de meses
        foreach ($this->periodos as $p) {
            $estado = data_get($iglesia->estados, $p, '-');
            $fila[] = $estado;
        }

        return $fila;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la cabecera (Fila 1)
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0D47A1']
                ]
            ],
        ];
    }


}
