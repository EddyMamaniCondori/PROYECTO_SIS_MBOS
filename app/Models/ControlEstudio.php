<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <<<< correcto
use Illuminate\Database\Eloquent\Model;

class ControlEstudio extends Model
{
    use HasFactory;

    protected $table = 'control_estudios';
    protected $primaryKey = 'id_control';

    protected $fillable = [
        'nivel',
        'nro_leccion',
        'fecha',
        'estudiante_id',
    ];

    // Relación: un control de estudio pertenece a un estudiante bíblico
    public function estudianteBiblico() : BelongsTo
    {
        return $this->belongsTo(EstudianteBiblico::class, 'estudiante_id', 'id_est');
    }
}
