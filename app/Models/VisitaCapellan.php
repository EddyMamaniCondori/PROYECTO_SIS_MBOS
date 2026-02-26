<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitaCapellan extends Model
{
    use HasFactory;

    protected $table = 'visita_capellans';
    protected $primaryKey = 'id_visita_cape';

    protected $fillable = [
        'fecha_visita',
        'nombre_visitado',
        'cant_present',
        'telefono',
        'hora',
        'motivo',
        'descripcion_lugar',
        'id_ue',
        'id_pastor',
    ];

    /**
     * Relación: La visita pertenece a una Unidad Educativa
     */
    public function unidadEducativa()
    {
        return $this->belongsTo(UnidadEducativa::class, 'id_ue', 'id_ue');
    }

    /**
     * Relación: La visita fue realizada por un Pastor
     */
    public function pastor()
    {
        return $this->belongsTo(Pastor::class, 'id_pastor', 'id_pastor');
    }
}
