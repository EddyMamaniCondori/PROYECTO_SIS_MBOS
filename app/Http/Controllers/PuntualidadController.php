<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\Exports\PuntualidadExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;


class PuntualidadController extends Controller
{

    /*🔹 Puntualidad// SATISFECHOS
    *'ver - puntualidad',
    */
    function __construct()
    {
        // index(): permissions 'ver puntualidad'
        // La etiqueta en la lista era 'ver - puntualidad', usaremos 'ver - puntualidad' para ser exactos.
        $this->middleware('permission:ver-puntualidad', ['only' => ['index']]);
    }

     public function index() //permissions 'ver puntualidad',
    {
        // Traemos todas las iglesias junto con su distrito
        $iglesias = DB::select("
                    SELECT 
                    xd.nombre as nombre_distrito,
                    xi.codigo, 
                    xi.nombre,
                    xi.tipo,
                    xi.lugar,
                    xp.anio,
                    -- puntualidad por mes
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 1) AS puntualidad_enero,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 2) AS puntualidad_febrero,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 3) AS puntualidad_marzo,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 4) AS puntualidad_abril,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 5) AS puntualidad_mayo,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 6) AS puntualidad_junio,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 7) AS puntualidad_julio,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 8) AS puntualidad_agosto,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 9) AS puntualidad_septiembre,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 10) AS puntualidad_octubre,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 11) AS puntualidad_noviembre,
                    MAX(xm.tipo) FILTER (WHERE xm.mes = 12) AS puntualidad_diciembre
                FROM iglesias xi 
                LEFT JOIN distritos xd ON xi.distrito_id = xd.id_distrito
                JOIN puntualidades xp ON xp.id_iglesia = xi.id_iglesia
                JOIN mes xm ON xm.id_puntualidad = xp.id_puntualidad
                WHERE xp.anio = 2026
                and xi.estado = true
                GROUP BY 
                    xd.nombre,
                    xi.codigo, 
                    xi.nombre,
                    xi.tipo,
                    xi.lugar,
                    xp.anio;
        ");
        return view('puntualidad.index', compact('iglesias'));
    }

    public function exportExcel()
    {
        return Excel::download(new PuntualidadExport, 'puntualidad.xlsx');
    }

    public function exportPdf()
    {
        $iglesias = DB::select("
            SELECT 
                xd.nombre as nombre_distrito,
                xi.codigo, 
                xi.nombre,
                xi.tipo,
                xi.lugar,
                xp.anio,

                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 1) AS INTEGER) AS puntualidad_enero,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 2) AS INTEGER) AS puntualidad_febrero,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 3) AS INTEGER) AS puntualidad_marzo,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 4) AS INTEGER) AS puntualidad_abril,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 5) AS INTEGER) AS puntualidad_mayo,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 6) AS INTEGER) AS puntualidad_junio,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 7) AS INTEGER) AS puntualidad_julio,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 8) AS INTEGER) AS puntualidad_agosto,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 9) AS INTEGER) AS puntualidad_septiembre,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 10) AS INTEGER) AS puntualidad_octubre,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 11) AS INTEGER) AS puntualidad_noviembre,
                CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 12) AS INTEGER) AS puntualidad_diciembre,

                (
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 1) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 2) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 3) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 4) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 5) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 6) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 7) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 8) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 9) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 10) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 11) AS INTEGER), 0) +
                    COALESCE(CAST(MAX(xm.tipo) FILTER (WHERE xm.mes = 12) AS INTEGER), 0)
                ) AS total_puntaje

            FROM iglesias xi 
            LEFT JOIN distritos xd ON xi.distrito_id = xd.id_distrito
            JOIN puntualidades xp ON xp.id_iglesia = xi.id_iglesia
            JOIN mes xm ON xm.id_puntualidad = xp.id_puntualidad
            WHERE xp.anio = 2026
            AND xi.estado = true
            GROUP BY 
                xd.nombre,
                xi.codigo, 
                xi.nombre,
                xi.tipo,
                xi.lugar,
                xp.anio
            ORDER BY total_puntaje DESC;

        ");

        $fecha = date('d/m/Y');
        $hora = date('H:i:s');

        $pdf = app('dompdf.wrapper');   // ← compatible con Laravel 12
        $pdf->loadView('pdf.puntualidad', compact('iglesias','fecha','hora'))
            ->setPaper('A4', 'landscape');
        return $pdf->stream('puntualidad.pdf');
        //return $pdf->download('puntualidad.pdf');
    }



}
