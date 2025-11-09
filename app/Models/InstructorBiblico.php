<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <<<< correcto
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'celular',
        'id_iglesia',
        'fecha_registro'
    ];

    // Relación: un instructor bíblico pertenece a una iglesia
    public function iglesia() : BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'id_iglesia', 'id_iglesia');
    }

    protected function edad(): Attribute //calcula la edad del instructor segun la fecha de nacimiento
    {
        return Attribute::make(
            get: fn() => $this->fecha_nacimiento ? 
                \Carbon\Carbon::parse($this->fecha_nacimiento)->age : null
        );
    }
}
