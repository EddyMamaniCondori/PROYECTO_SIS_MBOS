<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Genera extends Model
{
    use HasFactory;

    protected $table = 'generas';
    public $timestamps = true;
    public $incrementing = false; // porque la clave es compuesta

    protected $primaryKey = null; // No hay una sola PK

    protected $fillable = [
        'id_iglesia',
        'id_remesa',
        'mes',
        'anio',
    ];

    // 🔹 Relaciones
    public function iglesia()
    {
        return $this->belongsTo(Iglesia::class, 'id_iglesia', 'id_iglesia');
    }

    public function remesa()
    {
        return $this->belongsTo(Remesa::class, 'id_remesa', 'id_remesa');
    }

}
