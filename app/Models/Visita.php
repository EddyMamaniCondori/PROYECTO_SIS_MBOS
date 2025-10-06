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
        'fecha',
        'nombre_visitado',
        'cant_present',
        'telefono',
        'hora',
        'motivo',
        'descripcion_lugar',
        'pastor_id',
        'iglesia_id',
    ];

    // Relación: una visita pertenece a un pastor
    public function pastor() : BelongsTo
    {
        return $this->belongsTo(Pastor::class, 'pastor_id', 'id_pastor');
    }

    // Relación: una visita pertenece a una iglesia
    public function iglesia() : BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'iglesia_id', 'id_iglesia');
    }
}
