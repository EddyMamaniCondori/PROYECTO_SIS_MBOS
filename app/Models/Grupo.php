<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional, solo si no sigue convención plural)
    protected $table = 'grupos';

    // Clave primaria personalizada
    protected $primaryKey = 'id_grupo';

    // Si tu PK no es autoincremental entero, deberías agregar:
    // public $incrementing = true;
    // protected $keyType = 'int';

    // Campos que se pueden asignar en masa (fillable)
    protected $fillable = [
        'nombre',
        'nro_distritos',
        'administrativo_id',
    ];

    // Relación: un grupo pertenece a un distrito
    public function distritos() : HasMany
    {
        return $this->hasMany(Distrito::class, 'id_grupo', 'id_grupo');
    }

    // Relación: un grupo tiene 1 administrativo como consejero
    public function administrativo() : BelongsTo
    {
        return $this->belongsTo(Administrativo::class, 'administrativo_id', 'id_persona');
    }
}
