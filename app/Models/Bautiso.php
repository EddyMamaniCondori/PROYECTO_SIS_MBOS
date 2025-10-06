<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bautiso extends Model
{
    use HasFactory;

    protected $table = 'bautisos';
    protected $primaryKey = 'id_bautiso';

    protected $fillable = [
        'nombre',
        'ape_paterno',
        'ape_materno',
        'sexo',
        'fecha_nacimiento',
        'fecha_bautizo',
        'estudiante_biblico',
        'iglesia_id',
    ];

    // Relación: un bautiso pertenece a una iglesia
    public function iglesia() : BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'iglesia_id', 'id_iglesia');
    }
}

