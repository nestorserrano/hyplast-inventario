<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends SoftlandModel
{
    use HasFactory;

    protected $connection = 'softland';
    protected $table = 'C01.ARTICULO';
    protected $primaryKey = 'ARTICULO';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ARTICULO',
        'DESCRIPCION',
        'CLASIFICACION_1',
        'CLASIFICACION_2',
        'CLASIFICACION_3',
        'CLASIFICACION_4',
        'CLASIFICACION_5',
        'CLASIFICACION_6',
        'TIPO',
        'PESO_NETO',
        'PESO_BRUTO',
        'VOLUMEN',
    ];
}
