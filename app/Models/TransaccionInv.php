<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TransaccionInv extends SoftlandModel
{
    use HasFactory;

    protected $table = 'C01.TRANSACCION_INV';
    protected $primaryKey = 'CONSECUTIVO';  // Es un consecutivo interno
    protected $connection = 'softland';

    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'AUDIT_TRANS_INV',
        'CONSECUTIVO',
        'FECHA_HORA_TRANSAC',
        'ARTICULO',
        'BODEGA',
        'LOCALIZACION',
        'LOTE',
        'TIPO',
        'SUBTIPO',
        'SUBSUBTIPO',
        'NATURALEZA',
        'CANTIDAD',
        'COSTO_TOT_FISC_LOC',
        'COSTO_TOT_COMP_LOC',
        'PRECIO_TOTAL_LOCAL',
        'CONTABILIZADA',
        'FECHA',
        'CENTRO_COSTO',
        'CUENTA_CONTABLE',
        'ASIENTO_CARDEX',
        'DOC_FISCAL',
    ];

    public function auditTransInv(): BelongsTo
    {
        return $this->belongsTo(AuditTransInv::class, 'AUDIT_TRANS_INV', 'AUDIT_TRANS_INV');
    }

    public function articulo(): BelongsTo
    {
        return $this->belongsTo(Articulo::class, 'ARTICULO', 'ARTICULO');
    }

    public function bodega(): BelongsTo
    {
        return $this->belongsTo(Bodega::class, 'BODEGA', 'BODEGA');
    }

    public function bodegaDestino(): BelongsTo
    {
        return $this->belongsTo(Bodega::class, 'BODEGA_DESTINO', 'BODEGA');
    }
}
