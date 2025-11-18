<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiderLocal extends Model
{
    use HasFactory;

    // 1. Configuración de la tabla
    protected $table = 'lideres_local';
    protected $primaryKey = 'id_lideres';
    public $incrementing = true; // El campo 'id_' es auto-incrementable
    
    // 2. Campos rellenables (Fillable)
    protected $fillable = [
        'id_iglesia',
        'tipo',
        'Dir_Filial',
        'Dir_congregacion',
        'Anciano',
        'Diaconisas',
        'Diaconos',
        'EESS_Adultos',
        'EESS_Jovenes',
        'EESS_Niños',
        'GP',
        'Parejas_misioneras',
    ];

    // 3. Relación 1:1 (Inversa) con Iglesia
    /**
     * Obtiene la iglesia a la que pertenece este registro de líderes.
     */
    public function iglesia(): BelongsTo
    {
        return $this->belongsTo(Iglesia::class, 'id_iglesia', 'id_iglesia');
    }
}
