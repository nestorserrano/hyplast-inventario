<?php

namespace App\Http\Controllers;

use App\Models\AuditTransInv;
use App\Models\TransaccionInv;
use App\Models\Conjunto;
use App\Traits\HasPermissionChecks;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use DB;

class AuditTransInvController extends Controller
{
    use HasPermissionChecks;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$this->checkPermission('documentosaplica.view')) {
            Alert::error('Acceso Denegado', 'No tienes permiso para ver documentos de inventario aplicados.');
            return redirect()->route('home');
        }

        $filtroEstado = $request->get('estado', null);

        return view('pages.audit_trans_inv.index', compact('filtroEstado'));
    }

    /**
     * Get counters for dashboard
     */
    public function getCounters()
    {
        if (!$this->checkPermission('documentosaplica.view')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $total = AuditTransInv::count();
        $aprobados = AuditTransInv::whereNotNull('USUARIO_APRO')->count();
        $porAprobar = AuditTransInv::whereNull('USUARIO_APRO')->count();

        return response()->json([
            'total' => $total,
            'aprobados' => $aprobados,
            'por_aprobar' => $porAprobar
        ]);
    }

    /**
     * Get paquetes for filter
     */
    public function getPaquetes()
    {
        if (!$this->checkPermission('documentosaplica.view')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $paquetes = DB::connection('softland')
            ->table('C01.PAQUETE_INVENTARIO')
            ->select('PAQUETE_INVENTARIO', 'DESCRIPCION')
            ->orderBy('PAQUETE_INVENTARIO')
            ->get()
            ->map(function($p) {
                return [
                    'value' => $p->PAQUETE_INVENTARIO,
                    'text' => $p->PAQUETE_INVENTARIO . ' - ' . $p->DESCRIPCION
                ];
            });

        return response()->json($paquetes);
    }

    /**
     * Get consecutivos for filter
     */
    public function getConsecutivos()
    {
        if (!$this->checkPermission('documentosaplica.view')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        try {
            $consecutivos = DB::connection('softland')
                ->table('C01.AUDIT_TRANS_INV')
                ->select('CONSECUTIVO')
                ->distinct()
                ->whereNotNull('CONSECUTIVO')
                ->where('CONSECUTIVO', '!=', '')
                ->orderBy('CONSECUTIVO')
                ->pluck('CONSECUTIVO');

            return response()->json($consecutivos);
        } catch (\Exception $e) {
            \Log::error('Error en getConsecutivos: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        try {
            \Log::info('AuditTransInvController@data - Inicio');
            \Log::info('Request params: ' . json_encode($request->all()));

            // Query usando DB directo - SIN joins ni consultas adicionales
            $query = DB::connection('softland')->table('C01.AUDIT_TRANS_INV')
                ->select(
                    'AUDIT_TRANS_INV',
                    'CONSECUTIVO',
                    'FECHA_HORA',
                    'ASIENTO',
                    'PAQUETE_INVENTARIO',
                    'APLICACION',
                    'USUARIO',
                    'USUARIO_APRO',
                    'FECHA_HORA_APROB'
                );

            // Filtro por documento
            if ($request->filled('documento')) {
                $query->where('AUDIT_TRANS_INV', 'like', '%' . $request->documento . '%');
            }

            // Filtro por paquete
            if ($request->filled('paquete')) {
                $query->where('PAQUETE_INVENTARIO', $request->paquete);
            }

            // Filtro por consecutivo
            if ($request->filled('consecutivo')) {
                $query->where('CONSECUTIVO', $request->consecutivo);
            }

            // Filtro por aplicación
            if ($request->filled('aplicacion')) {
                $query->where('APLICACION', 'like', '%' . $request->aplicacion . '%');
            }

            // Filtro por estado de aprobación
            if ($request->filled('aprobado')) {
                if ($request->aprobado == '1') {
                    $query->whereNotNull('USUARIO_APRO');
                } elseif ($request->aprobado == '0') {
                    $query->whereNull('USUARIO_APRO');
                }
            }

            // Filtro por fecha
            if ($request->filled('fecha_desde')) {
                $query->where('FECHA_HORA', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->where('FECHA_HORA', '<=', $request->fecha_hasta . ' 23:59:59');
            }

            \Log::info('Query SQL: ' . $query->toSql());

            // Paginación manual porque DataTables::of() carga todo en memoria
            $start = $request->input('start', 0);
            $length = $request->input('length', 25);
            $draw = $request->input('draw', 1);

            // Ordenamiento
            $orderColumn = $request->input('order.0.column', 0);
            $orderDir = $request->input('order.0.dir', 'desc');

            // Mapeo de columnas para ordenamiento
            $columns = [
                0 => 'AUDIT_TRANS_INV',
                1 => 'AUDIT_TRANS_INV',
                2 => 'APLICACION',
                3 => 'FECHA_HORA',
                4 => 'ASIENTO',
                5 => 'PAQUETE_INVENTARIO',
                6 => 'CONSECUTIVO',
                7 => 'USUARIO',
                8 => 'USUARIO_APRO',
                9 => 'FECHA_HORA_APROB',
            ];

            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            } else {
                $query->orderBy('AUDIT_TRANS_INV', 'desc');
            }

            // Contar total de registros
            $totalRecords = DB::connection('softland')->table('C01.AUDIT_TRANS_INV')->count();

            // Clonar query para contar filtrados
            $queryClone = clone $query;
            $filteredRecords = $queryClone->count();

            // Aplicar paginación y obtener datos
            $data = $query->skip($start)->take($length)->get();

            // Procesar datos manualmente
            $result = [];
            $index = $start + 1;

            foreach ($data as $row) {
                // Generar botones con estándar del proyecto usando traducciones
                $btnVer = '<button type="button" class="btn btn-success btn-sm me-1 view-details" data-id="' . $row->AUDIT_TRANS_INV . '" data-toggle="tooltip" title="' . trans('hyplast.tooltips.show') . '">' .
                    trans('hyplast.buttons.show') . '</button>';

                // Botón asiento - siempre visible, deshabilitado si no tiene asiento
                if ($row->ASIENTO) {
                    $btnAsiento = '<a href="' . route('customer-statement.asiento', $row->ASIENTO) . '" target="_blank" class="btn btn-info btn-sm me-1" data-toggle="tooltip" title="Ver Asiento Contable">' .
                        trans('hyplast.buttons.asiento') . '</a>';
                } else {
                    $btnAsiento = '<button type="button" class="btn btn-info btn-sm me-1" disabled data-toggle="tooltip" title="Sin Asiento">' .
                        trans('hyplast.buttons.asiento') . '</button>';
                }

                $btnImprimir = '<a href="' . route('documentos-aplicados.print', $row->AUDIT_TRANS_INV) . '" target="_blank" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Imprimir">' .
                    trans('hyplast.buttons.print') . '</a>';

                $result[] = [
                    'DT_RowIndex' => $index++,
                    'documento' => $row->AUDIT_TRANS_INV,
                    'fecha' => $row->FECHA_HORA ? Carbon::parse($row->FECHA_HORA)->format('d/m/Y H:i') : '-',
                    'asiento' => $row->ASIENTO ?: '-',
                    'paquete' => $row->PAQUETE_INVENTARIO ?: '-',
                    'consecutivo' => $row->CONSECUTIVO ?: '-',
                    'aplicacion' => $row->APLICACION ?: '-',
                    'usuario' => $row->USUARIO ?: '-',
                    'usuario_apro' => $row->USUARIO_APRO ?: '-',
                    'fecha_aprobacion' => $row->FECHA_HORA_APROB ? Carbon::parse($row->FECHA_HORA_APROB)->format('d/m/Y H:i') : '-',
                    'total_lineas' => '-',
                    'estado' => $row->USUARIO_APRO
                        ? '<span class="badge badge-success">Aprobado</span>'
                        : '<span class="badge badge-warning">Pendiente</span>',
                    'action' => '<div class="d-flex justify-content-end"><div class="btn-group" role="group">' .
                        $btnVer . $btnAsiento . $btnImprimir .
                        '</div></div>'
                ];
            }

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en AuditTransInvController@data: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Error al cargar datos',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ], 500);
        }
    }

    /**
     * Get detail of a specific document
     */
    public function show($id)
    {
        if (!$this->checkPermission('documentosaplica.show')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver detalles de documentos de inventario aplicados.'
            ], 403);
        }

        try {
            // Obtener documento sin relaciones
            $documento = DB::connection('softland')
                ->table('C01.AUDIT_TRANS_INV')
                ->where('AUDIT_TRANS_INV', $id)
                ->first();

            if (!$documento) {
                return response('<div class="alert alert-danger">Documento no encontrado</div>', 404);
            }

            // Obtener transacciones manualmente
            $transacciones = DB::connection('softland')
                ->table('C01.TRANSACCION_INV as T')
                ->leftJoin('C01.ARTICULO as A', 'T.ARTICULO', '=', 'A.ARTICULO')
                ->where('T.AUDIT_TRANS_INV', $id)
                ->select(
                    'T.*',
                    'A.DESCRIPCION as articulo_descripcion'
                )
                ->orderBy('T.CONSECUTIVO')
                ->get();

            // Simular objetos para la vista
            $documento = (object) array_merge((array) $documento, [
                'usuarioCreador' => null,
                'usuarioAprobador' => null,
                'ajusteConfig' => null
            ]);

            // Agregar descripción de artículo a cada transacción
            foreach ($transacciones as $trans) {
                $trans->articulo = (object) ['DESCRIPCION' => $trans->articulo_descripcion ?? 'N/A'];
            }

            // Determinar clase de fila según estado de aprobación
            $infoRowClass = $documento->USUARIO_APRO ? 'info-row-approved' : 'info-row-pending';

            $html = view('pages.audit_trans_inv.show', compact('documento', 'transacciones', 'infoRowClass'))->render();

            return response($html);

        } catch (\Exception $e) {
            \Log::error('Error en show: ' . $e->getMessage());
            return response('<div class="alert alert-danger">Error al cargar el documento: ' . $e->getMessage() . '</div>', 500);
        }
    }

    /**
     * Imprimir documento
     */
    public function print($id)
    {
        if (!$this->checkPermission('documentosaplica.show')) {
            Alert::error('Acceso Denegado', 'No tienes permiso para ver documentos de inventario aplicados.');
            return redirect()->route('documentos-aplicados.index');
        }

        $documento = AuditTransInv::with([
            'transacciones.articulo',
            'transacciones.bodega',
            'transacciones.bodegaDestino',
            'usuarioCreador',
            'usuarioAprobador',
            'ajusteConfig',
            'paqueteInventario',
            'consecutivo',
            'moduloOrigen'
        ])->findOrFail($id);

        // Obtener conjunto (información de la empresa) - Tabla: erpadmin.CONJUNTO
        $conjunto = Conjunto::where('CONJUNTO', session('conjunto', 'C01'))->first();

        // Preparar ruta del logo - Carpeta: public/imagen/conjunto
        $logoPath = null;
        if ($conjunto && $conjunto->LOGO) {
            $logoFiles = [
                $conjunto->LOGO,
                'logo' . strtolower(str_replace('C0', 'c0', session('conjunto', 'C01'))) . '.jpg',
                'logo' . session('conjunto', 'C01') . '.jpg',
                strtolower($conjunto->LOGO),
            ];

            foreach ($logoFiles as $logoFile) {
                $fullPath = public_path('imagen/conjunto/' . $logoFile);
                if (file_exists($fullPath)) {
                    $logoPath = asset('imagen/conjunto/' . $logoFile);
                    break;
                }
            }
        }

        return view('pages.audit_trans_inv.print', compact('documento', 'conjunto', 'logoPath'));
    }

    /**
     * Aprobar un documento de inventario
     */
    public function approve(Request $request)
    {
        try {
            // Verificar permiso
            if (!$this->checkPermission('documentosaplica.approve')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para aprobar documentos de inventario.'
                ], 403);
            }

            $documentoId = $request->input('documento_id');

            // Obtener usuario autenticado
            $user = auth()->user();

            // Verificar que el usuario tenga un softland_user asignado
            if (empty($user->softland_user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Su usuario no tiene un usuario de Softland asignado. Contacte al administrador.'
                ]);
            }

            // Obtener el documento usando query directo
            $documento = DB::connection('softland')
                ->table('C01.AUDIT_TRANS_INV')
                ->where('AUDIT_TRANS_INV', $documentoId)
                ->first();

            if (!$documento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Documento no encontrado.'
                ]);
            }

            // Verificar si ya está aprobado
            if ($documento->USUARIO_APRO) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este documento ya ha sido aprobado.'
                ]);
            }

            // Usar transacción para asegurar la consistencia
            DB::connection('softland')->transaction(function () use ($documentoId, $user) {
                DB::connection('softland')
                    ->table('C01.AUDIT_TRANS_INV')
                    ->where('AUDIT_TRANS_INV', $documentoId)
                    ->update([
                        'USUARIO_APRO' => $user->softland_user,
                        'FECHA_HORA_APROB' => Carbon::now()
                    ]);
            });

            Alert::success('Éxito', 'El documento ha sido aprobado correctamente.');

            return response()->json([
                'success' => true,
                'message' => 'Documento aprobado correctamente.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al aprobar documento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al aprobar el documento: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Desaprobar un documento de inventario
     */
    public function unapprove(Request $request)
    {
        try {
            // Verificar permiso
            if (!$this->checkPermission('desaprobar.documento.inventario')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para desaprobar documentos de inventario.'
                ], 403);
            }

            $documentoId = $request->input('documento_id');

            // Obtener usuario autenticado
            $user = auth()->user();

            // Verificar que el usuario tenga un softland_user asignado
            if (empty($user->softland_user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Su usuario no tiene un usuario de Softland asignado. Contacte al administrador.'
                ]);
            }

            // Obtener el documento
            $documento = DB::connection('softland')
                ->table('C01.AUDIT_TRANS_INV')
                ->where('AUDIT_TRANS_INV', $documentoId)
                ->first();

            if (!$documento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Documento no encontrado.'
                ]);
            }

            // Verificar si no está aprobado
            if (!$documento->USUARIO_APRO) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este documento no está aprobado.'
                ]);
            }

            // Usar transacción
            DB::connection('softland')->transaction(function () use ($documentoId) {
                DB::connection('softland')
                    ->table('C01.AUDIT_TRANS_INV')
                    ->where('AUDIT_TRANS_INV', $documentoId)
                    ->update([
                        'USUARIO_APRO' => null,
                        'FECHA_HORA_APROB' => null
                    ]);
            });

            Alert::success('Éxito', 'El documento ha sido desaprobado correctamente.');

            return response()->json([
                'success' => true,
                'message' => 'Documento desaprobado correctamente.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al desaprobar documento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al desaprobar el documento: ' . $e->getMessage()
            ]);
        }
    }
}
