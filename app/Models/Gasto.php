<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'gastos';

    protected $primaryKey = 'id_gasto';

    protected $fillable = [
        'monto',
        'observacion',
        'mes',
        'anio',
    ];

    public function genera()
    {
        return $this->hasMany(Genera::class, 'id_gasto', 'id_gasto');
    }

    public function iglesias()
    {
        return $this->hasManyThrough(
            Iglesia::class,
            Genera::class,
            'id_gasto',    // FK en genera
            'id_iglesia',  // PK en iglesias
            'id_gasto',    // PK en gastos
            'id_iglesia'   // FK en genera
        );
    }

    public function remesas()
    {
        return $this->hasManyThrough(
            Remesa::class,
            Genera::class,
            'id_gasto',    // FK en genera
            'id_remesa',   // PK en remesas
            'id_gasto',    // PK en gastos
            'id_remesa'    // FK en genera
        );
    }


}
