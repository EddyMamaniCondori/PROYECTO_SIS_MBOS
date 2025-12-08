<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'accion',
        'tabla',
        'id_registro',
        'fecha_hora'
    ];
}
