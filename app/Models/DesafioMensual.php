<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <<<< correcto
use Illuminate\Database\Eloquent\Model;

class DesafioMensual extends Model
{
      use HasFactory;

    protected $table = 'desafio_mensuales';
    protected $primaryKey = 'id_desafio';

    protected $fillable = [
        'mes',
        'anio',
        'desafio_visitacion',
        'desafio_bautiso',
        'desafio_inst_biblicos',
        'desafios_est_biblicos',
        'visitas_alcanzadas',
        'bautisos_alcanzados',
        'instructores_alcanzados',
        'estudiantes_alcanzados',
        'iglesia_id',
        'pastor_id',
    ];

    // Relación: un desafío pertenece a una iglesia
    public function iglesia() : BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'iglesia_id', 'id_iglesia');
    }

    // Relación: un desafío pertenece a un pastor
    public function pastor() : BelongsTo
    {
        return $this->belongsTo(Pastor::class, 'pastor_id', 'id_pastor');
    }
}
