<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RemesaFilial extends Model
{
    use HasFactory;

    protected $table = 'remesas_filiales';
    protected $primaryKey = 'id_remesa';
    public $incrementing = false;

    protected $fillable = [
        'id_remesa',
        'ofrenda',
        'diezmo',
        'pro_templo',
        'fondo_local',
        'monto_remesa'
    ];

    public function remesa()
    {
        return $this->belongsTo(Remesa::class, 'id_remesa', 'id_remesa');
    }
}
