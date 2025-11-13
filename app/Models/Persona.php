<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Pastor;

use App\Models\Persona; 

class Persona extends Authenticatable
{
     use HasFactory, Notifiable, HasRoles; 

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
        'email',
        'password',
        'estado',

        'celular',
        'ciudad',
        'zona',
        'calle',
        'nro',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'estado' => 'boolean',
    ];
    /*****  *********************************************
     * Relaciónes
     */

    //realacion principal 1:1 con pastor
    public function pastor(): HasOne
    {
        return $this->hasOne(Pastor::class, 'id_pastor', 'id_persona');
    }
        //realacion principal 1:1 con personal
    public function personal(): HasOne
    {
        return $this->hasOne(Personal::class, 'id_personal', 'id_persona');
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
