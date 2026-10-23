<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReporteGeneralRemesasExport implements FromArray, WithHeadings, WithColumnFormatting, WithStrictNullComparison
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
            'DIEZMOS',
            'OFRENDAS',
            '40% MISIÓN',
            '60% LOCAL',
            'PRO-TEMPLO',
            'TOTAL MES'
        ];
    }

    public function array(): array
    {
        $data = [];
        $sumDiezmo = 0;
        $sumOfrenda = 0;
        $sumProTemplo = 0;
        $totalGeneral = 0;

        foreach ($this->reporteMensual as $r) {
            $mesAnio = $this->mesesNombres[$r->mes] . ' - ' . $r->anio;

            $diezmo = (float) ($r->diezmo_total ?? 0);
            $ofrenda = (float) ($r->ofrenda_total ?? 0);
            $proTemplo = (float) ($r->pro_templo_total ?? 0);

            $ofrenda40 = $ofrenda * 0.40;
            $ofrenda60 = $ofrenda * 0.60;
            $subTotal = $diezmo + $ofrenda + $proTemplo;

            $data[] = [
                $mesAnio,
                $diezmo,
                $ofrenda,
                $ofrenda40,
                $ofrenda60,
                $proTemplo,
                $subTotal
            ];

            $sumDiezmo += $diezmo;
            $sumOfrenda += $ofrenda;
            $sumProTemplo += $proTemplo;
            $totalGeneral += $subTotal;
        }

        $data[] = ['', '', '', '', '', '', ''];

        $data[] = [
            'TOTALES',
            (float) $sumDiezmo,
            (float) $sumOfrenda,
            (float) ($sumOfrenda * 0.40),
            (float) ($sumOfrenda * 0.60),
            (float) $sumProTemplo,
            (float) $totalGeneral
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
