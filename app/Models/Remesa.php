<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remesa extends Model
{
    use HasFactory;

    protected $table = 'remesas';

    protected $primaryKey = 'id_remesa';

    protected $fillable = [
        'cierre',
        'deposito',
        'documentacion',
        'fecha_entrega',
        'fecha_limite',
        'estado',
        'observacion',
        'mes',
        'anio',
        'monto',
    ];


    public function genera()
    {
        return $this->hasMany(Genera::class, 'id_remesa', 'id_remesa');
    }

    public function iglesias()
    {
        return $this->hasManyThrough(
            Iglesia::class,
            Genera::class,
            'id_remesa',   // FK en genera
            'id_iglesia',  // PK en iglesias
            'id_remesa',   // PK en remesas
            'id_iglesia'   // FK en genera
        );
    }

    public function gastos()
    {
        return $this->hasManyThrough(
            Gasto::class,
            Genera::class,
            'id_remesa',  // FK en genera
            'id_gasto',   // PK en gastos
            'id_remesa',  // PK en remesas
            'id_gasto'    // FK en genera
        );
    }
}
