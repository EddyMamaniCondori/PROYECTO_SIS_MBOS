<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <<<< correcto
class EstudianteBiblico extends Model
{
    use HasFactory;

    protected $table = 'estudiante_biblicos';
    protected $primaryKey = 'id_est';

    protected $fillable = [
        'nombre',
        'ape_paterno',
        'ape_materno',
        'sexo',
        'opcion_contacto',
        'edad',
        'celular',
        'estado_civil',
        'ci',
        'curso_biblico_usado',
        'fecha_registro',
        'bautizado',
        'id_iglesia',
    ];

    // Relación: un estudiante bíblico pertenece a una iglesia
    public function iglesia() : BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'id_iglesia', 'id_iglesia');
    }

    // Un estudiante bíblico tiene muchos controles de estudio
    public function controlEstudios() : HasMany
    {
        return $this->hasMany(ControlEstudio::class, 'estudiante_id', 'id_est');
    }
}
