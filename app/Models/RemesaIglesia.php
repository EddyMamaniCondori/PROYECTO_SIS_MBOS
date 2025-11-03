<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemesaIglesia extends Model
{
    use HasFactory;

    protected $table = 'remesas_iglesias';
    protected $primaryKey = 'id_remesa';
    public $incrementing = false;

    protected $fillable = ['id_remesa', 'monto'];

    // Relación inversa 1:1
    public function remesa()
    {
        return $this->belongsTo(Remesa::class, 'id_remesa', 'id_remesa');
    }

}
