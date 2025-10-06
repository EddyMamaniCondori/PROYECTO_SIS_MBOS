<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pastor extends Model
{
     use HasFactory;

    protected $table = 'pastors';
    protected $primaryKey = 'id_pastor';
    public $incrementing = false;

    protected $fillable = [
        'id_pastor',
        'fecha_ordenacion',
        'ordenado',
        'cargo',
        'nro_distritos',
    ];

    /**********************************************************
     * RELACIONES
     */

    //Relación inversa con Persona 1:1 
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_pastor', 'id_persona');
    }

    // realacion principal  1:1 con distrito (verficado)
    public function distrito(): HasOne
    {
        return $this->hasOne(Distrito::class);
    }

    
    //realacion n:m con direges(distrito_pastor)
    public function distritos(): BelongsToMany
    {
        return $this->belongsToMany(Distrito::class, 'diriges', 'id_pastor', 'id_distrito')
                    ->withPivot('fecha_asignacion', 'fecha_finalizacion')
                    ->withTimestamps();
    }

    // Relación: un pastor tiene muchas visitas
    public function visitas() : HasMany
    {
        return $this->hasMany(Visita::class, 'pastor_id', 'id_pastor');
    }

    // Relación: un pastor tiene muchos desafíos mensuales
    public function desafiosMensuales() : HasMany
    {
        return $this->hasMany(DesafioMensual::class, 'pastor_id', 'id_pastor');
    }


    /************************************************************
    * Accesor: Estado de ordenación en texto
    */

    public function getEstadoOrdenacionAttribute(): string
    {
        return $this->ordenado ? 'Ordenado' : 'No ordenado';
    }

    public static function PastorDC() // devuelve todos los datos completos del pastor
    {
        return self::with('persona')->get();
    }
}
