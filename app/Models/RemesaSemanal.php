<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemesaSemanal extends Model
{
    protected $table = 'remesas_semanales';
    
    protected $fillable = [
        'id_remesa', 'nro_semana', 'diezmo_total', 'ofrenda_total', 
        'pacto_total', 'especiales_total', 'pro_templo_total', 'detalle_filas'
    ];

    // ESTO ES CLAVE: Convierte el JSON de la DB en un Array de PHP automáticamente
    protected $casts = [
        'detalle_filas' => 'array',
    ];

    public function remesaMensual()
    {
        return $this->belongsTo(Remesa::class, 'id_remesa', 'id_remesa');
    }
}
