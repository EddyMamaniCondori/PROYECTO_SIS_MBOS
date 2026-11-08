<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate; // Importante para calcular letras de columnas dinámicas
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class TesorerosOroDetalladoExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected $tesoreros;
    protected $mesesNombres;
    protected $periodo;

    public function __construct($tesoreros, $mesesNombres, $periodo)
    {
        $this->tesoreros = $tesoreros;
        $this->mesesNombres = $mesesNombres;
        $this->periodo = $periodo;
    }

    private function formatTime($secs)
    {
        if ($secs === null || $secs === '') return '-';
        $isNegative = $secs < 0;
        $secs = abs((int)$secs);
        $d = floor($secs / 86400);
        $h = floor(($secs % 86400) / 3600);
        $m = floor(($secs % 3600) / 60);
        $s = $secs % 60;

        $sign = $isNegative ? '-' : '';
        return sprintf("%s%dd %02d:%02d:%02d", $sign, $d, $h, $m, $s);
    }

    public function headings(): array
    {
        // FILA 1: Títulos principales (Se fusionarán más adelante)
        $row1 = [
            'Nro', 'Cod', 'Iglesia', 'Distrito', 'Lugar', 'Tipo', 'Categoría'
        ];

        // FILA 2: Subtítulos (Quedarán vacíos en las columnas base porque se fusionan verticalmente)
        $row2 = [
            '', '', '', '', '', '', ''
        ];

        // Construcción dinámica de los meses
        foreach ($this->mesesNombres as $nombreMes) {
            $mesUpper = strtoupper($nombreMes);

            // Fila 1: Ponemos el nombre del mes y 4 espacios vacíos que luego fusionaremos (Merge)
            array_push($row1, $mesUpper, '', '', '', '');

            // Fila 2: Ponemos las sub-columnas exactas
            array_push($row2, 'Fecha Límite', 'Fecha Entrega', 'Días Retraso', 'Tiempo de Demora', 'Estrellas');
        }

        // Agregamos las columnas finales de totales
        array_push($row1, 'PUNTAJE TOTAL', 'TOTAL TIEMPO DEMORA');
        array_push($row2, '', ''); // Vacíos para fusionar verticalmente

        return [$row1, $row2];
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->tesoreros as $index => $t) {
            $row = [
                $index + 1,
                $t->codigo_iglesia,
                $t->nombre_iglesia,
                $t->nombre_distrito,
                $t->lugar,
                $t->tipo_iglesia,
                $t->categoria
            ];

            $detalles = is_string($t->detalles_meses) ? json_decode($t->detalles_meses) : $t->detalles_meses;
            $detallesColeccion = collect($detalles);

            foreach ($this->mesesNombres as $numMes => $nombreMes) {
                $detalleMes = $detallesColeccion->firstWhere('mes', $numMes);

                if (!$detalleMes || is_null($detalleMes->fecha_entrega)) {
                    array_push($row, 'Sin Envío', 'Sin Envío', 'Sin Envío', 'Sin Envío', '0 pts');
                } else {
                    $limite = Carbon::parse($detalleMes->fecha_limite)->format('d/m/Y H:i');
                    $entrega = Carbon::parse($detalleMes->fecha_entrega)->format('d/m/Y H:i');
                    $segundosMes = strtotime($detalleMes->fecha_entrega) - strtotime($detalleMes->fecha_limite);

                    $row[] = $limite;
                    $row[] = $entrega;
                    $row[] = $detalleMes->dias_retraso . 'd';
                    $row[] = $this->formatTime($segundosMes);
                    $row[] = $detalleMes->estrellas . ' pts';
                }
            }

            $row[] = $t->puntuacion_total;
            $row[] = $this->formatTime($t->total_segundos_demora);

            $data[] = $row;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Fusionar celdas de las columnas base verticalmente (A1:A2, B1:B2... G1:G2)
        for ($i = 1; $i <= 7; $i++) {
            $col = Coordinate::stringFromColumnIndex($i);
            $sheet->mergeCells("{$col}1:{$col}2");
        }

        // 2. Fusionar celdas de los Meses horizontalmente (H1:L1, M1:Q1, etc.)
        $colIndex = 8; // Empezamos en la columna 8 (H)
        foreach ($this->mesesNombres as $nombreMes) {
            $startCol = Coordinate::stringFromColumnIndex($colIndex);
            $endCol = Coordinate::stringFromColumnIndex($colIndex + 4);
            $sheet->mergeCells("{$startCol}1:{$endCol}1");
            $colIndex += 5; // Saltamos 5 columnas para el siguiente mes
        }

        // 3. Fusionar celdas de Totales verticalmente al final
        $totalCol1 = Coordinate::stringFromColumnIndex($colIndex);
        $totalCol2 = Coordinate::stringFromColumnIndex($colIndex + 1);
        $sheet->mergeCells("{$totalCol1}1:{$totalCol1}2");
        $sheet->mergeCells("{$totalCol2}1:{$totalCol2}2");

        // 4. Dar formato visual (Azul, Centrado y con Bordes Blancos para que parezca una tabla limpia)
        $lastCol = $totalCol2;
        $sheet->getStyle("A1:{$lastCol}2")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '0D47A1'] // Azul institucional
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'FFFFFF'] // Borde blanco para separar los encabezados
                ]
            ]
        ]);

        return [];
    }
}
