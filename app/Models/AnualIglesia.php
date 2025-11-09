<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class AnualIglesia extends Model
{
    
    use HasFactory;

    protected $table = 'anual_iglesias';
    protected $primaryKey = 'id_desafio_iglesia';
    
    protected $fillable = [
        'desafio_instructores',
        'instructores_alcanzados',
        'desafio_estudiantes',
        'estudiantes_alcanzados',
        'id_desafio',
        'id_iglesia',
    ];

    // 🔹 Relación con Desafio
    public function desafio()
    {
        return $this->belongsTo(Desafio::class, 'id_desafio', 'id_desafio');
    }

    // 🔹 Relación con Iglesia
    public function iglesia()
    {
        return $this->belongsTo(Iglesia::class, 'id_iglesia', 'id_iglesia');
    }
}
