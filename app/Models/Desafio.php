<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desafio extends Model
{
    use HasFactory;

    protected $table = 'desafios';
    protected $primaryKey = 'id_desafio'; // si usas un id personalizado
    public $timestamps = true;

    protected $fillable = [
        'desafio_bautizo',
        'bautizos_alcanzados',
        'anio',
        'estado',
        'id_distrito',
        'id_pastor',
    ];

    // 🔹 Relación con Distrito
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'id_distrito', 'id_distrito');
    }

    // 🔹 Relación con Pastor
    public function pastor()
    {
        return $this->belongsTo(Pastor::class, 'id_pastor', 'id_pastor');
    }
    public function anualIglesias()
    {
        return $this->hasMany(AnualIglesia::class, 'id_desafio', 'id_desafio');
    }
    public function mensuales()
    {
        return $this->hasMany(Mensual::class, 'id_desafio', 'id_desafio');
    }

    /**🔹 belongsToMany() establece la relación M:N.
🔹 withPivot() permite acceder a los campos adicionales del pivote (desafio, alcanzado).
🔹 withTimestamps() mantiene actualizadas las columnas created_at y updated_at. */

    public function eventos()
    {
        return $this->belongsToMany(DesafioEvento::class, 'asigna_desafios', 'id_desafio', 'id_desafio_evento')
                    ->withPivot('desafio', 'alcanzado')
                    ->withTimestamps();
    }

}
