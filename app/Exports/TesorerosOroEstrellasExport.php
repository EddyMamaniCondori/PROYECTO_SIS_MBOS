<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TesorerosOroEstrellasExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected $tesoreros;
    protected $mesesNombres;
    protected $periodo;
    protected $tipoReporte;
    protected $conDetalles;

    public function __construct($tesoreros, $mesesNombres, $periodo, $tipoReporte)
    {
        $this->tesoreros = $tesoreros;
        $this->mesesNombres = $mesesNombres;
        $this->periodo = $periodo;
        $this->tipoReporte = $tipoReporte;
        $this->conDetalles = ($tipoReporte === 'detalles');
    }

    public function headings(): array
    {
        // Cabeceras base
        $headings = [
            'Nro',
            'Cod',
            'Iglesia',
            'Distrito',
            'Lugar',
            'Tipo',
            'Categoría'
        ];

        if ($this->tipoReporte !== 'lista') {
            // Agregamos dinámicamente los nombres de los meses seleccionados
            foreach ($this->mesesNombres as $nombreMes) {
                $headings[] = strtoupper($nombreMes);
            }

            if ($this->conDetalles) {
                $headings[] = 'PUNTAJE TOTAL';
            }
        } else {
            // Cabeceras para el formato "Solo Lista"
            $headings[] = 'RECEPCIONA';
            $headings[] = 'RECIBIDO';
        }

        return $headings;
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->tesoreros as $index => $t) {
            // Columnas base
            $row = [
                $index + 1,
                $t->codigo_iglesia,
                $t->nombre_iglesia,
                $t->nombre_distrito,
                $t->lugar,
                $t->tipo_iglesia,
                $t->categoria
            ];

            if ($this->tipoReporte !== 'lista') {
                $detalles = is_string($t->detalles_meses) ? json_decode($t->detalles_meses) : $t->detalles_meses;
                $detallesColeccion = collect($detalles);

                // Procesamos cada mes dinámicamente
                foreach ($this->mesesNombres as $numMes => $nombreMes) {
                    $detalleMes = $detallesColeccion->firstWhere('mes', $numMes);

                    if (!$detalleMes || is_null($detalleMes->fecha_entrega)) {
                        $row[] = 'Sin Envío';
                    } else {
                        if ($this->conDetalles) {
                            $row[] = $detalleMes->estrellas . ' pts (Retraso: ' . $detalleMes->dias_retraso . 'd)';
                        } else {
                            $row[] = $detalleMes->estrellas . ' pts';
                        }
                    }
                }

                if ($this->conDetalles) {
                    $row[] = $t->puntuacion_total;
                }
            } else {
                // Rellenamos las últimas dos columnas vacías para "Solo Lista"
                $row[] = '';
                $row[] = '';
            }

            $data[] = $row;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la primera fila (Cabeceras)
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '0D47A1']]
            ],
        ];
    }
}
