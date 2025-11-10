<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Personal extends Model
{
    use HasFactory;
    protected $table = 'personales';
    protected $primaryKey = 'id_personal';
    public $incrementing = false;

    protected $fillable = [
        'id_personal',
        'fecha_ingreso',
        'fecha_finalizacion',
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personal', 'id_persona');
    }
}

    