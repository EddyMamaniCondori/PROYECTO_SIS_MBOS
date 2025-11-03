<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\RemesasImport;
use Maatwebsite\Excel\Facades\Excel;

class RemesaExcelController extends Controller
{
    public function index() //VERIFICADO
    {
        return view('remesas.import_remsa');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new RemesasImport, $request->file('file'));

        return redirect()->back()->with('success', 'Archivo Excel importado correctamente.');
    }
}
