<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DesafioEvento extends Model
{
    //

    use HasFactory;

    protected $table = 'desafio_eventos';
    protected $primaryKey = 'id_desafio_evento';

    protected $fillable = [
        'nombre',
        'anio',
        'estado',
        'fecha_inicio',
        'fecha_final',
    ];

    // 🔹 Relación muchos a muchos con Desafio
    public function desafios()
    {
        return $this->belongsToMany(Desafio::class, 'asigna_desafios', 'id_desafio_evento', 'id_desafio')
                    ->withPivot('desafio', 'alcanzado')
                    ->withTimestamps();
    }
}
