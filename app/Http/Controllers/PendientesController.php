<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PendientesController extends Controller
{
    public function index() //VERIFICADO
    {
        $datos = DB::select("
            select 	xd.nombre as nombre_distrito,
                    xp.nombre as nombre_p, xp.ape_paterno, xp.ape_materno,
                    xi.codigo, xi.nombre,
                    xi.tipo, xi.lugar, xg.*,
                    xr.estado
            from generas xg
            join remesas xr on xg.id_remesa = xr.id_remesa 
            join iglesias xi on xg.id_iglesia = xi.id_iglesia
            left join distritos xd on xi.distrito_id = xd.id_distrito
            left join personas xp on xd.id_pastor = xp.id_persona
            where xr.estado = 'PENDIENTE'
            and anio = 2025
            order by nombre_distrito 
        ");  // Trae todos los registros de la tabla asociada a RemesaImport
        $datos_totales = DB::select("
            SELECT 
                xi.tipo,
                COUNT(*) AS total
            FROM generas xg
            JOIN remesas xr ON xg.id_remesa = xr.id_remesa 
            JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            WHERE xg.anio = 2025
            AND xr.estado = 'PENDIENTE'
            GROUP BY xi.tipo
            ORDER BY xi.tipo;
        ");

        $c_iglesias = 0;
        $c_grupo = 0;
        $c_filiales = 0;
        

        foreach ($datos_totales as $dato) {
            $tipo = strtolower(trim($dato->tipo)); // Minusculas y sin espacios extras
            $total = $dato->total;

            if (strpos($tipo, 'filial') !== false) {
                $c_filiales += $total;
            } elseif (strpos($tipo, 'grupo') !== false) {
                $c_grupo += $total;
            } elseif (strpos($tipo, 'iglesia') !== false) {
                $c_iglesias += $total;
            }
        }
        $total = $c_filiales+$c_grupo+$c_iglesias;
        return view('pendientes.index_anual', compact('datos', 'c_iglesias', 'c_grupo', 'c_filiales', 'total')); // Pasa esos datos a la vista
    }

}
