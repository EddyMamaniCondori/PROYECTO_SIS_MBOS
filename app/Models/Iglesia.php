<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <<<< correcto
class Iglesia extends Model
{
     protected $table = 'iglesias';

    // Clave primaria personalizada
    protected $primaryKey = 'id_iglesia';

    protected $fillable = [
        'nombre',
        'feligresia',
        'feligrasia_asistente',
        'ciudad',
        'zona',
        'calle',
        'nro',
        'distrito_id',
    ];

    // Relación N:1 con Distrito
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id_distrito');
    }

    // Relación: una iglesia tiene muchas visitas
    public function visitas() : HasMany
    {
        return $this->hasMany(Visita::class, 'iglesia_id', 'id_iglesia');
    }

    // Relación: una iglesia tiene muchos bautisos
    public function bautisos() : HasMany
    {
        return $this->hasMany(Bautiso::class, 'iglesia_id', 'id_iglesia');
    }
    // Relación: una iglesia tiene muchos estudiantes bíblicos
    public function estudianteBiblicos() : HasMany
    {
        return $this->hasMany(EstudianteBiblico::class, 'iglesia_id', 'id_iglesia');
    }

    // Relación: una iglesia tiene muchos instructores bíblicos
    public function instructoreBiblicos() : HasMany
    {
        return $this->hasMany(InstructorBiblico::class, 'iglesia_id', 'id_iglesia');
    }
     // Relación: una iglesia tiene muchos desafíos mensuales
    public function desafiosMensuales() : HasMany
    {
        return $this->hasMany(DesafioMensual::class, 'iglesia_id', 'id_iglesia');
    }
}
