<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioBodega extends SoftlandModel
{
    // Conexión a Softland
    protected $table = 'C01.USUARIO_BODEGA';

    // Deshabilitar timestamps ya que Softland usa su propio sistema
    public $timestamps = false;

    // Esta es una tabla pivot, no tiene clave primaria única
    // Usamos clave compuesta
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'BODEGA',
        'USUARIO',
    ];

    protected $casts = [
        'BODEGA'      => 'string',
        'USUARIO'     => 'string',
        'RecordDate'  => 'datetime',
        'CreateDate'  => 'datetime',
    ];

    /**
     * Relación con Bodega
     */
    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'BODEGA', 'BODEGA');
    }

    /**
     * Obtener detalles del usuario (si existe en tabla USUARIO)
     */
    public function usuarioSoftland()
    {
        return $this->belongsTo(UsuarioSoftland::class, 'USUARIO', 'USUARIO');
    }
}
