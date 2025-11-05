<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class BlancoRemesa extends Model
{
    
    use HasFactory;

    protected $table = 'blanco_remesas';
    protected $primaryKey = 'id_blanco';

    protected $fillable = [
        'monto',
        'alcanzado',
        'diferencia',
        'anio',
        'id_distrito',
        'id_pastor',
    ];

    // 🔗 Relaciones

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'id_distrito', 'id_distrito');
    }

    public function pastor()
    {
        return $this->belongsTo(Pastor::class, 'id_pastor', 'id_pastor');
    }
}
