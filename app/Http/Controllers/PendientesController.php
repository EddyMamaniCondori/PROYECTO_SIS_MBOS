<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Distrito; 
class PendientesController extends Controller
{
    /**
     * 
     * 'ver anual-pendientes',
      *      'ver distrital-pendientes',
      *      'ver mensual-pendientes',
     */

    function __construct()
    {
        // index(): permision ver anual-pendientes
        // Nota: Esta función es un reporte anual general.
        $this->middleware('permission:ver anual-pendientes', ['only' => ['index', 'filtro_anual']]); 

        // index_distrital(): permision ver distrital-pendientes
        $this->middleware('permission:ver distrital-pendientes', ['only' => ['index_distrital','filtro_anual']]);

        // index_mensual(): permision ver mensual-pendientes
        $this->middleware('permission:ver mensual-pendientes', ['only' => ['index_mensual','filtro_anual']]);

        // La función 'filtro_anual' es el procesamiento del formulario de 'index', por lo que se agrupa con 'index'.
        
    }

    public function index() 
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
            and anio = 2026
            order by nombre_distrito 
        ");  // Trae todos los registros de la tabla asociada a RemesaImport
        $datos_totales = DB::select("
            SELECT 
                xi.tipo,
                COUNT(*) AS total
            FROM generas xg
            JOIN remesas xr ON xg.id_remesa = xr.id_remesa 
            JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            WHERE xg.anio = 2026
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

         $personal = DB::select("
            SELECT xp.id_persona, xp.nombre, xp.ape_paterno, xr.name
            FROM personas xp
            JOIN personales xpp ON xp.id_persona = xpp.id_personal
            JOIN model_has_roles xm ON xp.id_persona = xm.model_id 
            JOIN roles xr ON xm.role_id = xr.id
            WHERE xr.name LIKE 'Tesorero'
        ");


        return view('pendientes.index_anual', compact('datos', 'c_iglesias', 'c_grupo', 'c_filiales', 'total', 'personal')); // Pasa esos datos a la vista*/
        //return view('pendientes.pruebas');
    }

    public function filtro_anual(Request $request)
    {   
         $validated = $request->validate([
            'periodoInicio' => 'required|string',
            'periodoFinal' => 'required|string',
            'tipo' => 'required|array|min:1',
            'tipo.*' => 'string', // Opcional, para validar cada elemento del array
        ]);

        //dd($request);
        $periodoInicio = $request->input('periodoInicio'); // e.g. "03-2025"
        $periodoFinal = $request->input('periodoFinal');   // e.g. "12-2025"
        $tipos = $request->input('tipo', []);              // e.g. [1,2,3
        
        if (!$periodoInicio || !$periodoFinal) {
            return back()->with('error', 'Debe seleccionar ambos periodos');
        }

        // Extraemos mes y año para filtrar por separado
        [$mesInicio, $anioInicio] = array_map('intval', explode('-', $periodoInicio));
        [$mesFinal, $anioFinal] = array_map('intval', explode('-', $periodoFinal));

        if (count($tipos) === 0) {
            return back()->with('error', 'Debe seleccionar al menos un tipo');
        }
            
        // Para pasar el array de tipos a cadena para SQL, escapando bien:
        $placeholders = implode(',', array_fill(0, count($tipos), '?'));

        //dd($mesInicio, $mesFinal, $anioFinal, $anioInicio, $placeholders, $tipos);
        // Validar los datos si quieres
        $sql = "
        select  
            xd.nombre as nombre_distrito,
            xp.nombre as nombre_p, 
            xp.ape_paterno, 
            xp.ape_materno,
            xi.codigo, 
            xi.nombre,
            xi.tipo, 
            xi.lugar, 
            xg.*,
            xr.estado
        from generas xg
        join remesas xr on xg.id_remesa = xr.id_remesa 
        join iglesias xi on xg.id_iglesia = xi.id_iglesia
        left join distritos xd on xi.distrito_id = xd.id_distrito
        left join personas xp on xd.id_pastor = xp.id_persona
        where xr.estado = 'PENDIENTE'
        and (
            (xg.anio > ? OR (xg.anio = ? AND xg.mes >= ?))
            AND
            (xg.anio < ? OR (xg.anio = ? AND xg.mes <= ?))
        )
        and LOWER(xi.tipo) IN ($placeholders)
        order by nombre_distrito
        ";

        $params = [
            $anioInicio, $anioInicio, $mesInicio,
            $anioFinal, $anioFinal, $mesFinal,
            ...array_map('strtolower', $tipos)
        ];
        $datos = DB::select($sql, $params);


        $datos_totales = DB::select("
            SELECT 
                xi.tipo,
                COUNT(*) AS total
            FROM generas xg
            JOIN remesas xr ON xg.id_remesa = xr.id_remesa 
            JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            WHERE xr.estado = 'PENDIENTE'
            AND (
                (xg.anio > ? OR (xg.anio = ? AND xg.mes >= ?))
                AND
                (xg.anio < ? OR (xg.anio = ? AND xg.mes <= ?))
            )
            AND LOWER(xi.tipo) IN ($placeholders)
            GROUP BY xi.tipo
            ORDER BY xi.tipo
        ", $params);

        // Inicializas contadores
        $c_iglesias = 0;
        $c_grupo = 0;
        $c_filiales = 0;

        // Sumamos según coincidencia en el tipo (minusculas y trim)
        foreach ($datos_totales as $dato) {
            $tipo = strtolower(trim($dato->tipo));
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
        return view('pendientes.index_anual', compact('datos', 'c_iglesias', 'c_grupo', 'c_filiales', 'total'));
    }


    public function index_mensual() 
    {
        $datos = DB::select("
            SELECT
                xg.mes,
                xg.anio,
                COUNT(CASE WHEN LOWER(xi.tipo) = 'iglesia' THEN 1 END) AS nro_iglesias,
                COUNT(CASE WHEN LOWER(xi.tipo) = 'grupo' THEN 1 END) AS nro_grupos,
                COUNT(CASE WHEN LOWER(xi.tipo) = 'filial' THEN 1 END) AS nro_filiales,
                COUNT(*) AS total
            FROM generas xg
            JOIN remesas xr ON xg.id_remesa = xr.id_remesa
            JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
            WHERE xr.estado = 'PENDIENTE'
            GROUP BY xg.anio, xg.mes
            ORDER BY xg.anio, xg.mes;
        ");  
        return view('pendientes.index_mensual', compact('datos')); 
    }

    public function index_distrital() 
    {
        $persona = Auth::user(); 
        $distrito = Distrito::where('id_pastor', $persona->id_persona)->first();
        if (!$distrito) {
            return redirect()->route('panel')->with('error', 'No tienes un distrito asignado.');
        }
        $id_distrito = $distrito->id_distrito;

        $anio = 2025;

        $datos = DB::select("
                    SELECT 
                    xi.id_iglesia,
                    xi.codigo,
                    xi.nombre,
                    COALESCE(MAX(CASE WHEN xg.mes = 1 THEN xr.estado END), 'SIN REGISTRO') AS mes_enero,
                    COALESCE(MAX(CASE WHEN xg.mes = 2 THEN xr.estado END), 'SIN REGISTRO') AS mes_febrero,
                    COALESCE(MAX(CASE WHEN xg.mes = 3 THEN xr.estado END), 'SIN REGISTRO') AS mes_marzo,
                    COALESCE(MAX(CASE WHEN xg.mes = 4 THEN xr.estado END), 'SIN REGISTRO') AS mes_abril,
                    COALESCE(MAX(CASE WHEN xg.mes = 5 THEN xr.estado END), 'SIN REGISTRO') AS mes_mayo,
                    COALESCE(MAX(CASE WHEN xg.mes = 6 THEN xr.estado END), 'SIN REGISTRO') AS mes_junio,
                    COALESCE(MAX(CASE WHEN xg.mes = 7 THEN xr.estado END), 'SIN REGISTRO') AS mes_julio,
                    COALESCE(MAX(CASE WHEN xg.mes = 8 THEN xr.estado END), 'SIN REGISTRO') AS mes_agosto,
                    COALESCE(MAX(CASE WHEN xg.mes = 9 THEN xr.estado END), 'SIN REGISTRO') AS mes_septiembre,
                    COALESCE(MAX(CASE WHEN xg.mes = 10 THEN xr.estado END), 'SIN REGISTRO') AS mes_octubre,
                    COALESCE(MAX(CASE WHEN xg.mes = 11 THEN xr.estado END), 'SIN REGISTRO') AS mes_noviembre,
                    COALESCE(MAX(CASE WHEN xg.mes = 12 THEN xr.estado END), 'SIN REGISTRO') AS mes_diciembre
                FROM generas xg
                JOIN remesas xr ON xg.id_remesa = xr.id_remesa
                JOIN iglesias xi ON xg.id_iglesia = xi.id_iglesia
                WHERE xi.distrito_id = ?
                AND xg.anio = ?
                GROUP BY xi.id_iglesia, xi.codigo, xi.nombre
                ORDER BY xi.nombre;
        ",[$id_distrito, $anio]);  

        return view('pendientes.vista_distrital', compact('datos')); 
    }

}
