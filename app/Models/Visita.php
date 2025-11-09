<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;

    protected $table = 'visitas';
    protected $primaryKey = 'id_visita';

    protected $fillable = [
        'fecha_visita',
        'nombre_visitado',
        'cant_present',
        'telefono',
        'hora',
        'motivo',
        'descripcion_lugar',
        'id_iglesia',
    ];



    // Relación: una visita pertenece a una iglesia
    public function iglesia() : BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'id_iglesia', 'id_iglesia');
    }
}
