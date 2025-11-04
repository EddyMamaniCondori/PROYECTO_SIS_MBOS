<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Mes extends Model
{
     use HasFactory;

    protected $table = 'mes';
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = ['id_puntualidad', 'mes']; // Clave compuesta (solo informativo)

    protected $fillable = [
        'id_puntualidad',
        'mes',
        'tipo',
    ];

    // 🔹 Relación inversa
    public function puntualidad()
    {
        return $this->belongsTo(Puntualidad::class, 'id_puntualidad', 'id_puntualidad');
    }
}
