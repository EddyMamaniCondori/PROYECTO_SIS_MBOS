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
        'tipo',
        'fecha_bautizo',
        'id_iglesia',
    ];

    // Relación: un bautiso pertenece a una iglesia
    public function iglesia() : BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'id_iglesia', 'id_iglesia');
    }
}

