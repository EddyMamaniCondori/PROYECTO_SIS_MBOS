<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <<<< correcto
class Mensual extends Model
{
    use HasFactory;

    protected $table = 'mensuales';
    protected $primaryKey = 'id_mensual';

    protected $fillable = [
        'mes',
        'anio',
        'desafio_visitas',
        'visitas_alcanzadas',
        'fecha_limite',
        'id_desafio',
    ];

    // 🔹 Relación con Desafio
    public function desafio()
    {
        return $this->belongsTo(Desafio::class, 'id_desafio', 'id_desafio');
    }
}
