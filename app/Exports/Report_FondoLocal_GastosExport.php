<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison; // <-- NUEVA IMPORTACIÓN
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Report_FondoLocal_GastosExport implements FromArray, WithHeadings, WithColumnFormatting, WithStrictNullComparison // <-- SE AÑADIÓ AQUÍ
{
    protected $reporteMensual;
    protected $mesesNombres;

    public function __construct($reporteMensual, $mesesNombres)
    {
        $this->reporteMensual = $reporteMensual;
        $this->mesesNombres = $mesesNombres;
    }

    public function headings(): array
    {
        return [
            'MES / AÑO',
            'OFRENDAS (100%)',
            'FONDO LOCAL (60% OF.)',
            'PRO-TEMPLO',
            'TOTAL INGRESO F.L.',
            '(-) GASTOS',
            'SALDO FINAL'
        ];
    }

    public function array(): array
    {
        $data = [];

        $sumOfrenda = 0;
        $sumFondo60 = 0;
        $sumProTemplo = 0;
        $totalIngresoFL = 0;
        $totalGastos = 0;

        foreach ($this->reporteMensual as $r) {
            $mesAnio = $this->mesesNombres[$r->mes] . ' ' . $r->anio;

            $ofrenda = (float) ($r->ofrenda_total ?? 0);
            $proTemplo = (float) ($r->pro_templo_total ?? 0);
            $gasto = (float) ($r->gasto_mensual ?? 0);

            $fondo60 = $ofrenda * 0.60;
            $ingresoFL = $fondo60 + $proTemplo;
            $saldoFinal = $ingresoFL - $gasto;

            $data[] = [
                $mesAnio,
                $ofrenda,
                $fondo60,
                $proTemplo,
                $ingresoFL,
                $gasto,
                $saldoFinal
            ];

            $sumOfrenda += $ofrenda;
            $sumFondo60 += $fondo60;
            $sumProTemplo += $proTemplo;
            $totalIngresoFL += $ingresoFL;
            $totalGastos += $gasto;
        }

        $data[] = ['', '', '', '', '', '', ''];

        $saldoNetoAcumulado = $totalIngresoFL - $totalGastos;

        $data[] = [
            'TOTALES',
            (float) $sumOfrenda,
            (float) $sumFondo60,
            (float) $sumProTemplo,
            (float) $totalIngresoFL,
            (float) $totalGastos,
            (float) $saldoNetoAcumulado
        ];

        return $data;
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_00,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
