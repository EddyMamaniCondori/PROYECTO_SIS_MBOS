<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Pastor;

use App\Models\Persona; 

class Persona extends Model
{
     use HasFactory;

    protected $table = 'personas';
    protected $primaryKey = 'id_persona';
    public $incrementing = true;

    /************************************************
     * Campos asignables 
     */
    protected $fillable = [
        'nombre',
        'ape_paterno',
        'ape_materno',
        'fecha_nac',
        'ci',
        'celular',
        'ciudad',
        'zona',
        'calle',
        'nro',
    ];


    /**************************************************
     * Relaciónes
     */

    //realacion principal 1:1 con pastor
    public function pastor(): HasOne
    {
        return $this->hasOne(Pastor::class, 'id_pastor', 'id_persona');
    }

    // Una persona puede ser un administrativo
    public function administrativo() :HasOne
    {
        return $this->hasOne(Administrativo::class, 'id_persona', 'id_persona');
    }

    /***************************************************
     * FUNCIONES PARA EL MODELO DE PERSONA
     */

    //nombre completo
    public function getNombreCompleto(): string
    {
        return "{$this->nombre} {$this->ape_paterno} {$this->ape_materno}";
    }

    


}
