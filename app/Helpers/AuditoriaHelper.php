<?php
namespace App\Helpers;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;

class AuditoriaHelper
{
    public static function registrar($accion, $tabla, $idRegistro = null)
    {
        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion'     => $accion,
            'tabla'      => $tabla,
            'id_registro'=> $idRegistro,
            'fecha_hora' => now()
        ]);
    }
}
