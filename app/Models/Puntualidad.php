<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Puntualidad extends Model
{
     use HasFactory;

    protected $table = 'puntualidades';
    protected $primaryKey = 'id_puntualidad';
    public $timestamps = true;

    protected $fillable = ['año'];

    // 🔹 Relación 1:N con Mes
    public function meses()
    {
        return $this->hasMany(Mes::class, 'id_puntualidad', 'id_puntualidad');
    }

    public function iglesia()
    {
        return $this->belongsTo(Iglesia::class, 'id_iglesia', 'id_iglesia');
    }



}
