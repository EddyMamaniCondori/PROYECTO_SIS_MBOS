<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <<<< correcto
use Illuminate\Database\Eloquent\Model;

class InstructorBiblico extends Model
{
    use HasFactory;

    protected $table = 'instructor_biblicos';
    protected $primaryKey = 'id_instructor';

    protected $fillable = [
        'nombre',
        'ape_paterno',
        'ape_materno',
        'sexo',
        'fecha_nacimiento',
        'edad',
        'celular',
        'iglesia_id',
    ];

    // Relación: un instructor bíblico pertenece a una iglesia
    public function iglesia() : BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'iglesia_id', 'id_iglesia');
    }
}
