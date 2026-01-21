<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AsignaDesafio extends Model
{
    use HasFactory;

    protected $table = 'asigna_desafios';
    protected $primaryKey = 'id_asigna_desafio';

    protected $fillable = [
        'id_desafio',
        'id_desafio_evento',
        'desafio',
        'alcanzado',
    ];

    // 🔹 Relación con Desafio
    public function desafio()
    {
        return $this->belongsTo(Desafio::class, 'id_desafio', 'id_desafio');
    }

    // 🔹 Relación con DesafioEvento (CAMPAÑAS)
    public function evento()
    {
        return $this->belongsTo(DesafioEvento::class, 'id_desafio_evento', 'id_desafio_evento');
    }
}
