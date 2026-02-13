<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remesa extends Model
{
    use HasFactory;

    protected $table = 'remesas';

    protected $primaryKey = 'id_remesa';
    public $timestamps = true;

    protected $fillable = [
        'cierre',
        'deposito',
        'documentacion',
        'fecha_entrega',
        'fecha_limite',
        'estado_dias',
        'estado',
        'observacion',
        'id_personal',
    ];


    public function genera()
    {
        return $this->hasMany(Genera::class, 'id_remesa', 'id_remesa');
    }

    public function iglesias()
    {
        return $this->belongsToMany(Iglesia::class, 'genera', 'id_remesa', 'id_iglesia')
                    ->withPivot(['mes', 'año'])
                    ->withTimestamps();
    }
    public function remesaIglesia()
    {
        return $this->hasOne(RemesaIglesia::class, 'id_r emesa', 'id_remesa');
    }

    public function remesaFilial()
    {
        return $this->hasOne(RemesaFilial::class, 'id_remesa', 'id_remesa');
    }

    public function personal()
    {
        // belongsTo(Modelo, clave_foranea_en_esta_tabla, clave_primaria_en_la_otra_tabla)
        return $this->belongsTo(Personal::class, 'id_personal', 'id_personal');
    }


    /*public function iglesias()
    {
        return $this->hasManyThrough(
            Iglesia::class,
            Genera::class,
            'id_remesa',   // FK en genera
            'id_iglesia',  // PK en iglesias
            'id_remesa',   // PK en remesas
            'id_iglesia'   // FK en genera
        );
    }*/

    /*public function gastos()
    {
        return $this->hasManyThrough(
            Gasto::class,
            Genera::class,
            'id_remesa',  // FK en genera
            'id_gasto',   // PK en gastos
            'id_remesa',  // PK en remesas
            'id_gasto'    // FK en genera
        );
    }*/
}
