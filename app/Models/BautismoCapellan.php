<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BautismoCapellan extends Model
{
    use HasFactory;
    protected $table = 'bautismo_capellans';
    protected $primaryKey = 'id_bautiso';

    protected $fillable = [
        'tipo',
        'fecha_bautizo',
        'id_ue',
    ];

    /**
     * Relación inversa (N:1): Muchos bautismos pertenecen a una Unidad Educativa.
     */
    public function unidadEducativa()
    {
        // 'id_ue' es la llave foránea en esta tabla
        // 'id_ue' es la llave local en la tabla unidad_educativas
        return $this->belongsTo(UnidadEducativa::class, 'id_ue', 'id_ue');
    }
}
