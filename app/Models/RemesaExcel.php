<?php

// app/Models/RemesaExcel.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemesaExcel extends Model
{
    use HasFactory;

    protected $table = 'remesas_excel';
    protected $fillable = [
        'codigo', 'nombre',
        'valor_1', 'mes_1',
        'valor_2', 'mes_2',
        'valor_3', 'mes_3',
        'valor_4', 'mes_4',
        'valor_5', 'mes_5',
        'valor_6', 'mes_6',
        'valor_7', 'mes_7',
        'valor_8', 'mes_8',
        'valor_9', 'mes_9',
        'valor_10', 'mes_10',
        'valor_11', 'mes_11',
        'valor_12', 'mes_12',
        'total'
    ];
}
