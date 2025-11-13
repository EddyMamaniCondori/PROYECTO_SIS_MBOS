<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use App\Imports\RemesasImport;
use App\Models\RemesaExcel;
use App\Models\Iglesia;
use App\Models\Remesa;
use App\Models\Genera;
use App\Models\RemesaFilial;
use App\Models\RemesaIglesia;
use Carbon\Carbon;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
class RemesaExcelController extends Controller
{
    public function index() //VERIFICADO //permission  'ver - remesas excel',
    {
        $remesas = RemesaExcel::all();  // Trae todos los registros de la tabla asociada a RemesaImport
        return view('remesas.import_remsa', compact('remesas')); // Pasa esos datos a la vista
    }

    public function import(Request $request) //permission  'importar - remesas excel',
    {
        //dd('si llego para exportar');
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new RemesasImport, $request->file('file'));

        return redirect()->back()->with('success', 'Archivo Excel importado correctamente.');
    }

    public function destroy(string $id) //permission  'elimar - remesas excel',
    {
        DB::beginTransaction();
        try {
            $remesa = RemesaExcel::find($id);
            $remesa->delete();

            DB::commit(); // Confirma la transacción

            return redirect()->back()->with('success', 'Registro eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revierte la transacción en caso de error

            return redirect()->back()->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }


    public function procesarRemesas($anio) // permissions 'guardar - remesas excel',
    {
        //dd('llego hasta qui', $anio);
        $meses = [
            'ene' => 1, 'feb' => 2, 'mar' => 3, 'abr' => 4, 'mayo' => 5,
            'jun' => 6, 'jul' => 7, 'ago' => 8, 'set' => 9,
            'oct' => 10, 'nov' => 11, 'dic' => 12,
        ];

        $remesasExcel = RemesaExcel::all(); //sacamos todos los registros

        foreach ($remesasExcel as $remesaExcel) { // recoremos los registros
            //DB::beginTransaction();
            //try {
                for ($i = 1; $i <= 12; $i++) {
                    $valor = $remesaExcel->{"valor_$i"} ?? null;
                    $nombreMes = strtolower(trim($remesaExcel->{"mes_$i"} ?? ''));
                    $numeroMes = $meses[$nombreMes] ?? null;


                    if ($numeroMes !== null) {
                        $remesaPivot = Genera::join('iglesias', 'iglesias.id_iglesia', '=', 'generas.id_iglesia')
                            ->where('iglesias.codigo', $remesaExcel->codigo)
                            ->where('iglesias.tipo', '!=', 'Filial')
                            ->where('generas.mes', $numeroMes)
                            ->where('generas.anio', $anio)
                            ->first();


                        if ($remesaPivot) {

                            $remesa = Remesa::find($remesaPivot->id_remesa);
                            $remesa->estado = 'ENTREGADO';
                            $remesa->save();

                            $remesaIglesia = RemesaIglesia::find($remesa->id_remesa);
                            $remesaIglesia->monto = $valor;
                            $remesaIglesia->save();
                        }
                    }
                }


                //DB::commit();


                $remesaExcel->delete();

            //} catch (\Exception $e) {
               // DB::rollBack();
                //continue;
            //}
        }

        return redirect()->back()->with('success', 'Proceso terminado.');
    }



}
