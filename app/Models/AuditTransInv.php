<?php

namespace App\Models;

use App\Models\AuditTransInv;
use App\Models\TransaccionInv;
use App\Models\Usuario;
use App\Models\AjusteConfig;
use App\Models\PaqueteInventario;
use App\Models\ConsecutivoCI;
use App\Models\DdModulo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class AuditTransInv extends SoftlandModel
{
    use HasFactory;

    protected $table = 'C01.AUDIT_TRANS_INV';
    protected $primaryKey = 'AUDIT_TRANS_INV';
    protected $connection = 'softland';

    public $incrementing = true;  // Es un INT autoincremental
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'AUDIT_TRANS_INV',
        'CONSECUTIVO',
        'USUARIO',
        'FECHA_HORA',
        'MODULO_ORIGEN',
        'APLICACION',
        'REFERENCIA',
        'ASIENTO',
        'USUARIO_APRO',
        'FECHA_HORA_APROB',
        'PAQUETE_INVENTARIO',
        'AJUSTE_CONFIG',
    ];

    public function transacciones(): HasMany
    {
        return $this->hasMany(TransaccionInv::class, 'AUDIT_TRANS_INV', 'AUDIT_TRANS_INV');
    }

    public function usuarioCreador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'USUARIO', 'USUARIO');
    }

    public function usuarioAprobador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'USUARIO_APRO', 'USUARIO');
    }

    public function ajusteConfig(): BelongsTo
    {
        return $this->belongsTo(AjusteConfig::class, 'AJUSTE_CONFIG', 'AJUSTE_CONFIG');
    }

    public function paqueteInventario(): BelongsTo
    {
        return $this->belongsTo(PaqueteInventario::class, 'PAQUETE_INVENTARIO', 'PAQUETE_INVENTARIO');
    }

    public function consecutivo(): BelongsTo
    {
        return $this->belongsTo(ConsecutivoCI::class, 'CONSECUTIVO', 'CONSECUTIVO');
    }

    public function moduloOrigen(): BelongsTo
    {
        return $this->belongsTo(DdModulo::class, 'MODULO_ORIGEN', 'MODULO');
    }
}
