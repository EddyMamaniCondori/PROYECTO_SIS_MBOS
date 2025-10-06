<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <<<< correcto
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Administrativo extends Model
{
     use HasFactory;

    protected $table = 'administrativos';
    protected $primaryKey = 'id_persona';
    public $incrementing = false; // porque PK es FK a Persona
    protected $keyType = 'unsignedBigInteger';

    protected $fillable = [
        'id_persona',
        'cargo',
        'ministerio',
    ];

    // Relación 1:1 con Persona
    public function persona() : BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    // Un administrativo puede ser consejero de muchos grupos
    public function grupos() : HasMany
    {
        return $this->hasMany(Grupo::class, 'administrativo_id', 'id_persona');
    }

    //****************************************************** */
    public static function AdministrativoDC() // devuelve todos los datos completos del pastor
    {
        return self::with('persona')->get();
    }
}
