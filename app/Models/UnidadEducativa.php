<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadEducativa extends Model
{
    use HasFactory;
    protected $table = 'unidad_educativas';
    protected $primaryKey = 'id_ue';
    public $incrementing = true;

    // 4. Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'estado',
        'sw_cambio',
        'año',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'sw_cambio' => 'boolean',
    ];

    /**
     * Relación (1:N): Una UE tiene muchas visitas
     */
    public function visitas()
    {
        return $this->hasMany(VisitaCapellan::class, 'id_ue', 'id_ue');
    }

    /**
     * Relación: Una Unidad Educativa tiene muchos Bautismos
     */
    public function bautismos()
    {
        // 1. Clase relacionada: BautismoCapellan
        // 2. Llave foránea en la tabla bautismo_capellans: 'id_ue'
        // 3. Llave local en la tabla unidad_educativas: 'id_ue'
        return $this->hasMany(BautismoCapellan::class, 'id_ue', 'id_ue');
    }
}
