<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Genera extends Model
{
    use HasFactory;

    protected $table = 'genera';

    public $incrementing = false; // clave primaria no autoincremental
    protected $primaryKey = null; // porque es compuesta

    protected $fillable = [
        'id_iglesia',
        'id_remesa',
        'id_gasto',
        'mes',
        'anio',
    ];

    public function iglesia() : BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'id_iglesia', 'id_iglesia');
    }

    public function remesa() : BelongsTo
    {
        return $this->belongsTo(Remesa::class, 'id_remesa', 'id_remesa');
    }

    public function gasto() : BelongsTo
    {
        return $this->belongsTo(Gasto::class, 'id_gasto', 'id_gasto');
    }

}
