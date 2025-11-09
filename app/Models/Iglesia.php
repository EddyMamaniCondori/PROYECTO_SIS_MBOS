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
        'estado',
        'codigo',
        'feligresia',
        'feligresia_asistente',
        'ciudad',
        'zona',
        'calle',
        'nro',
        'lugar',
        'tipo',
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
        return $this->hasMany(Visita::class, 'id_iglesia', 'id_iglesia');
    }

    // Relación: una iglesia tiene muchos bautisos
    public function bautisos() : HasMany
    {
        return $this->hasMany(Bautiso::class, 'id_iglesia', 'id_iglesia');
    }
    // Relación: una iglesia tiene muchos estudiantes bíblicos
    public function estudianteBiblicos() : HasMany //verificado
    {
        return $this->hasMany(EstudianteBiblico::class, 'id_iglesia', 'id_iglesia');
    }

    // Relación: una iglesia tiene muchos instructores bíblicos
    public function instructoreBiblicos() : HasMany //vefificado
    {
        return $this->hasMany(InstructorBiblico::class, 'id_iglesia', 'id_iglesia');
    }
     // Relación: una iglesia tiene muchos desafíos mensuales

    public function remesas()
    {
        return $this->belongsToMany(Remesa::class, 'genera', 'id_iglesia', 'id_remesa')
                    ->withPivot(['mes', 'año'])
                    ->withTimestamps();
    }

    public function puntualidades()
    {
        return $this->hasMany(Puntualidad::class, 'id_iglesia', 'id_iglesia');
    }
    
    public function anualIglesias()
    {
        return $this->hasMany(AnualIglesia::class, 'id_iglesia', 'id_iglesia');
    }


}
