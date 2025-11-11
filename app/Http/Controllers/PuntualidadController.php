<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PuntualidadController extends Controller
{
    public function index()
    {
 

        // Traemos todas las iglesias junto con su distrito
        /* postgrstsql$iglesias = DB::select("
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
                WHERE xp.anio = 2025
                and xi.estado = true
                GROUP BY 
                    xd.nombre,
                    xi.codigo, 
                    xi.nombre,
                    xi.tipo,
                    xi.lugar,
                    xp.anio;
        ");*/

        $iglesias = DB::select("
                    SELECT 
                        xd.nombre as nombre_distrito,
                        xi.codigo, 
                        xi.nombre,
                        xi.tipo,
                        xi.lugar,
                        xp.anio,
                        MAX(CASE WHEN xm.mes = 1 THEN xm.tipo ELSE NULL END) AS puntualidad_enero,
                        MAX(CASE WHEN xm.mes = 2 THEN xm.tipo ELSE NULL END) AS puntualidad_febrero,
                        MAX(CASE WHEN xm.mes = 3 THEN xm.tipo ELSE NULL END) AS puntualidad_marzo,
                        MAX(CASE WHEN xm.mes = 4 THEN xm.tipo ELSE NULL END) AS puntualidad_abril,
                        MAX(CASE WHEN xm.mes = 5 THEN xm.tipo ELSE NULL END) AS puntualidad_mayo,
                        MAX(CASE WHEN xm.mes = 6 THEN xm.tipo ELSE NULL END) AS puntualidad_junio,
                        MAX(CASE WHEN xm.mes = 7 THEN xm.tipo ELSE NULL END) AS puntualidad_julio,
                        MAX(CASE WHEN xm.mes = 8 THEN xm.tipo ELSE NULL END) AS puntualidad_agosto,
                        MAX(CASE WHEN xm.mes = 9 THEN xm.tipo ELSE NULL END) AS puntualidad_septiembre,
                        MAX(CASE WHEN xm.mes = 10 THEN xm.tipo ELSE NULL END) AS puntualidad_octubre,
                        MAX(CASE WHEN xm.mes = 11 THEN xm.tipo ELSE NULL END) AS puntualidad_noviembre,
                        MAX(CASE WHEN xm.mes = 12 THEN xm.tipo ELSE NULL END) AS puntualidad_diciembre
                    FROM iglesias xi 
                    LEFT JOIN distritos xd ON xi.distrito_id = xd.id_distrito
                    JOIN puntualidades xp ON xp.id_iglesia = xi.id_iglesia
                    JOIN mes xm ON xm.id_puntualidad = xp.id_puntualidad
                    WHERE xp.anio = 2025
                    AND xi.estado = true
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
}
