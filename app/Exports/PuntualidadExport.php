<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PuntualidadExport implements FromArray, WithHeadings
{

    public function array(): array
    {
        $iglesias = DB::select("
            SELECT 
    xd.nombre as nombre_distrito, 
    xi.codigo, 
    xi.nombre, 
    xi.tipo, 
    xi.lugar, 
    xp.anio, 
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
WHERE xp.anio = 2025 and xi.estado = true 
GROUP BY xd.nombre, xi.codigo, xi.nombre, xi.tipo, xi.lugar, xp.anio
ORDER BY nombre_distrito
        ");

        return json_decode(json_encode($iglesias), true);
    }

    public function headings(): array
    {
        return [
            'Distrito',
            'Código',
            'Iglesia',
            'Tipo',
            'Lugar',
            'Año',
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
    }
}
