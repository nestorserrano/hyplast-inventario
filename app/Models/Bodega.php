<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends SoftlandModel
{
    use HasFactory;

    protected $connection = 'softland';
    protected $table = 'C01.BODEGA';
    protected $primaryKey = 'BODEGA';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'BODEGA',
        'NOMBRE',
        'TIPO',
        'TELEFONO',
        'DIRECCION',
        'CONSEC_TRASLADOS',
        'CODIGO_ESTABLECIMIENTO',
        'MERCADO_LIBRE',
        'U_SUCURSAL',
        'U_COORDINADAS',
        'NO_STOCK_NEGATIVO',
    ];

    // Accessors para compatibilidad
    public function getIdAttribute()
    {
        return $this->BODEGA;
    }

    public function getCodeAttribute()
    {
        return $this->BODEGA;
    }

    public function getNameAttribute()
    {
        return $this->NOMBRE;
    }

    public function getDESCRIPCIONAttribute()
    {
        return $this->NOMBRE;
    }

    /**
     * Usuarios que tienen acceso a esta bodega
     */
    public function usuariosConAcceso()
    {
        return $this->hasMany(UsuarioBodega::class, 'BODEGA', 'BODEGA');
    }
}
