<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dirige extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'diriges';

    // Como no tiene id autoincremental, desactivamos los incrementos
    public $incrementing = false;

    // Si la clave primaria no es 'id', podemos omitirla o dejarla vacía
    protected $primaryKey = null;

    // Atributos asignables en masa
    protected $fillable = [
        'id_distrito',
        'id_pastor',
        'fecha_asignacion',
        'fecha_finalizacion',
        'año',
    ];

    // Relaciones
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'id_distrito', 'id_distrito');
    }

    public function pastor()
    {
        return $this->belongsTo(Pastor::class, 'id_pastor', 'id_pastor');
    }
}
