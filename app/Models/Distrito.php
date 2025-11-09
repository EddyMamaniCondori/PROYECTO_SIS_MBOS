<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Distrito extends Model
{
    //
    protected $table = 'distritos';
    protected $primaryKey = 'id_distrito';

    protected $fillable = [
        'nombre',
        'nro_iglesias',
        'id_pastor',
        'id_grupo',
        'sw_cambio',
        'estado',
        'fecha_asignacion',
        'año'
    ];
    
    /************************************************
     * Relaciónes 
     */

    //Relación inversa con Pastor 1:1 (VERIFICADO)
    public function pastor(): BelongsTo
    {
        return $this->belongsTo(Pastor::class);
    }


    //N:M con pastores
    public function pastores(): BelongsToMany
    {
        return $this->belongsToMany(Pastor::class, 'diriges', 'id_distrito', 'id_pastor')
                    ->withPivot('fecha_asignacion', 'fecha_finalizacion')
                    ->withTimestamps();
    }

    // Relación 1:N con Iglesias
    public function iglesias(): hasMany
    {
        return $this->hasMany(Iglesia::class, 'distrito_id', 'id_distrito');
    } 

    //Relacion N:1 con Grupo
    // Un distrito pertenece a un grupo
    public function grupo() : BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    public function blancos()
    {
        return $this->hasMany(BlancoRemesa::class, 'id_distrito', 'id_distrito');
    }

    public function desafios()
    {
        return $this->hasMany(Desafio::class, 'id_distrito', 'id_distrito');
    }

}
