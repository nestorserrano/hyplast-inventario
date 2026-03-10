<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\Articulo;
use App\Models\Bodega;
use App\Models\User;
use App\Models\Conjunto;
use App\Traits\HasPermissionChecks;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use DB;

class DocumentoInvController extends Controller
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
        // Verificar permiso usando el trait (funciona con roles)
        if (!$this->checkPermission('documentosinv.view')) {
            Alert::error('Acceso Denegado', 'No tienes permiso para ver documentos de inventario.');
            return redirect()->route('welcome');
        }

        // Obtener filtro desde URL si existe
        $filtroEstado = $request->get('estado', null);

        return view('pages.documento_inv.index', compact('filtroEstado'));
    }

    /**
     * Obtener contadores de documentos para dashboard
     */
    public function getCounters()
    {
        if (!$this->checkPermission('documentosinv.view')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $total = Inventory::count();
        $aprobados = Inventory::where('APROBADO', 'S')->count();
        $porAprobar = Inventory::where('APROBADO', '!=', 'S')->orWhereNull('APROBADO')->count();

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
        if (!$this->checkPermission('documentosinv.view')) {
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
        if (!$this->checkPermission('documentosinv.view')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $consecutivos = Inventory::select('CONSECUTIVO')
            ->distinct()
            ->whereNotNull('CONSECUTIVO')
            ->orderBy('CONSECUTIVO')
            ->pluck('CONSECUTIVO');

        return response()->json($consecutivos);
    }
    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        // Verificar permiso
        if (!$this->checkPermission('documentosinv.view')) {
            return response()->json([
                'error' => 'No tienes permiso para ver documentos de inventario.'
            ], 403);
        }

        try {
            // DEBUG: Verificar que llegue al método
            \Log::info('DocumentoInvController@data - Inicio');
            \Log::info('Request params: ' . json_encode($request->all()));

            $query = Inventory::query()->with(['lineas', 'usuarioCreador', 'usuarioAprobador']);

            // Filtro por documento
            if ($request->filled('documento')) {
                $query->where('DOCUMENTO_INV', 'like', '%' . $request->documento . '%');
            }

            // Filtro por paquete
            if ($request->filled('paquete')) {
                $query->where('PAQUETE_INVENTARIO', $request->paquete);
            }

            // Filtro por consecutivo
            if ($request->filled('consecutivo')) {
                $query->where('CONSECUTIVO', $request->consecutivo);
            }

            // Filtro por estado de aprobación
            if ($request->filled('aprobado')) {
                if ($request->aprobado == '1') {
                    $query->where('APROBADO', 'S');
                } elseif ($request->aprobado == '0') {
                    $query->where(function($q) {
                        $q->where('APROBADO', '!=', 'S')
                          ->orWhereNull('APROBADO');
                    });
                }
            }

            // Filtro por fecha
            if ($request->filled('fecha_desde')) {
                try {
                    $fechaDesde = Carbon::parse($request->fecha_desde)->format('Y-m-d');
                    $query->whereDate('FECHA_DOCUMENTO', '>=', $fechaDesde);
                } catch (\Exception $e) {
                    \Log::error('Error parseando fecha_desde: ' . $e->getMessage());
                }
            }

            if ($request->filled('fecha_hasta')) {
                try {
                    $fechaHasta = Carbon::parse($request->fecha_hasta)->format('Y-m-d');
                    $query->whereDate('FECHA_DOCUMENTO', '<=', $fechaHasta);
                } catch (\Exception $e) {
                    \Log::error('Error parseando fecha_hasta: ' . $e->getMessage());
                }
            }

            \Log::info('Query SQL: ' . $query->toSql());

            return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('documento', function ($row) {
                return $row->DOCUMENTO_INV;
            })
            ->addColumn('fecha', function ($row) {
                return $row->FECHA_DOCUMENTO ? Carbon::parse($row->FECHA_DOCUMENTO)->format('d/m/Y') : '-';
            })
            ->addColumn('fecha_creacion', function ($row) {
                return $row->FECHA_HOR_CREACION ? Carbon::parse($row->FECHA_HOR_CREACION)->format('d/m/Y H:i') : '-';
            })
            ->addColumn('paquete', function ($row) {
                return $row->PAQUETE_INVENTARIO ?: '-';
            })
            ->addColumn('consecutivo', function ($row) {
                return $row->CONSECUTIVO ?: '-';
            })
            ->addColumn('usuario', function ($row) {
                return $row->usuarioCreador ? $row->usuarioCreador->NOMBRE : ($row->USUARIO ?: '-');
            })
            ->addColumn('usuario_apro', function ($row) {
                return $row->usuarioAprobador ? $row->usuarioAprobador->NOMBRE : ($row->USUARIO_APRO ?: '-');
            })
            ->addColumn('fecha_aprobacion', function ($row) {
                return $row->FECHA_HORA_APROB ? Carbon::parse($row->FECHA_HORA_APROB)->format('d/m/Y H:i') : '-';
            })
            ->addColumn('total_lineas', function ($row) {
                try {
                    return $row->lineas ? $row->lineas->count() : 0;
                } catch (\Exception $e) {
                    \Log::error('Error contando lineas: ' . $e->getMessage());
                    return 0;
                }
            })
            ->addColumn('estado', function ($row) {
                if ($row->APROBADO === 'S') {
                    return '<span class="badge badge-success">Aprobado</span>';
                } else {
                    return '<span class="badge badge-warning">Pendiente</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex justify-content-start" style="gap: 5px;">';

                // Botón mostrar
                $btn .= '<button type="button" class="btn btn-success btn-sm view-details"
                            data-id="' . $row->DOCUMENTO_INV . '"
                            data-toggle="tooltip"
                            title="' . trans('hyplast.tooltips.show') . '">' .
                            trans('hyplast.buttons.show') .
                        '</button>';

                // Lógica de aprobación
                $estaAprobado = ($row->APROBADO === 'S' && !empty($row->USUARIO_APRO) && !empty($row->FECHA_HORA_APROB));
                $puedeAprobar = $this->checkPermission('documentosaplica.approve');
                $puedeDesaprobar = $this->checkPermission('desaprobar.documento.inventario');

                // BOTÓN APROBAR - Siempre visible, habilitado solo si está pendiente y tiene permiso
                if ($estaAprobado) {
                    $btn .= '<button type="button" class="btn btn-secondary btn-sm" disabled
                                data-toggle="tooltip"
                                title="Documento ya aprobado">
                                <i class="fas fa-stamp"></i>
                                <span class="hidden-xs hidden-sm"> Aprobado</span>
                            </button>';
                } else {
                    if ($puedeAprobar) {
                        $btn .= '<button type="button" class="btn btn-primary btn-sm approve-document"
                                    data-id="' . $row->DOCUMENTO_INV . '"
                                    data-toggle="tooltip"
                                    title="Aprobar Documento">
                                    <i class="fas fa-stamp"></i>
                                    <span class="hidden-xs hidden-sm"> Aprobar</span>
                                </button>';
                    } else {
                        $btn .= '<button type="button" class="btn btn-secondary btn-sm" disabled
                                    data-toggle="tooltip"
                                    title="No tiene permiso para aprobar">
                                    <i class="fas fa-stamp"></i>
                                    <span class="hidden-xs hidden-sm"> Aprobar</span>
                                </button>';
                    }
                }

                // BOTÓN DESAPROBAR - Siempre visible, habilitado solo si está aprobado y tiene permiso
                if ($estaAprobado && $puedeDesaprobar) {
                    $btn .= '<button type="button" class="btn btn-warning btn-sm unapprove-document"
                                data-id="' . $row->DOCUMENTO_INV . '"
                                data-toggle="tooltip"
                                title="Desaprobar Documento">
                                <i class="fas fa-ban"></i>
                                <span class="hidden-xs hidden-sm"> Desaprobar</span>
                            </button>';
                } else {
                    $btn .= '<button type="button" class="btn btn-secondary btn-sm" disabled
                                data-toggle="tooltip"
                                title="' . ($estaAprobado ? 'No tiene permiso para desaprobar' : 'Documento no aprobado') . '">
                                <i class="fas fa-ban"></i>
                                <span class="hidden-xs hidden-sm"> Desaprobar</span>
                            </button>';
                }

                // Botón imprimir
                $btn .= '<a href="' . route('documentos_inv.print', $row->DOCUMENTO_INV) . '"
                           target="_blank"
                           class="btn btn-info btn-sm"
                           data-toggle="tooltip"
                           title="Imprimir">' .
                           trans('hyplast.buttons.print') .
                        '</a>';

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['estado', 'action'])
            ->make(true);

        } catch (\Exception $e) {
            \Log::error('Error en DocumentoInvController@data: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Error al cargar datos',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * TEST: Endpoint simple para verificar datos
     */
    public function testData()
    {
        try {
            $data = Inventory::with(['lineas'])->take(5)->get();

            $result = [];
            foreach ($data as $doc) {
                $result[] = [
                    'DOCUMENTO_INV' => $doc->DOCUMENTO_INV,
                    'PAQUETE' => $doc->PAQUETE_INVENTARIO,
                    'CONSECUTIVO' => $doc->CONSECUTIVO,
                    'FECHA' => $doc->FECHA_DOCUMENTO,
                    'USUARIO' => $doc->USUARIO,
                    'LINEAS' => $doc->lineas->count()
                ];
            }

            return response()->json([
                'success' => true,
                'total' => Inventory::count(),
                'sample_data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Get detail of a specific document
     */
    public function show($id)
    {
        // Verificar permiso
        if (!$this->checkPermission('documentosinv.show')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver detalles de documentos de inventario.'
            ], 403);
        }

        $documento = Inventory::with([
            'lineas.articulo',
            'lineas.bodega',
            'lineas.bodegaDestino',
            'usuarioCreador',
            'usuarioAprobador',
            'paqueteInventario',
            'consecutivo'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $documento
        ]);
    }

    /**
     * Imprimir documento
     */
    public function print($id)
    {
        // Verificar permiso
        if (!$this->checkPermission('documentosinv.show')) {
            Alert::error('Acceso Denegado', 'No tienes permiso para ver documentos de inventario.');
            return redirect()->route('documentos_inv.index');
        }

        $documento = Inventory::with([
            'lineas.articulo',
            'lineas.bodega',
            'lineas.bodegaDestino',
            'usuarioCreador',
            'usuarioAprobador',
            'paqueteInventario',
            'consecutivo'
        ])->findOrFail($id);

        // Obtener conjunto (información de la empresa) - Tabla: erpadmin.CONJUNTO
        $conjunto = Conjunto::where('CONJUNTO', session('conjunto', 'C01'))->first();

        // Preparar ruta del logo - Carpeta: public/images/conjunto
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

        return view('pages.documento_inv.print', compact('documento', 'conjunto', 'logoPath'));
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

            // Obtener el documento
            $documento = Inventory::find($documentoId);

            if (!$documento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Documento no encontrado.'
                ]);
            }

            // Verificar si ya está aprobado
            if ($documento->APROBADO === 'S') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este documento ya ha sido aprobado.'
                ]);
            }

            // Logging antes de guardar
            \Log::info('Aprobando documento', [
                'documento_id' => $documentoId,
                'usuario' => $user->name,
                'softland_user' => $user->softland_user,
                'estado_actual' => $documento->APROBADO
            ]);

            // Usar transacción para asegurar la consistencia
            DB::transaction(function () use ($documento, $user) {
                $documento->USUARIO_APRO = $user->softland_user;
                $documento->FECHA_HORA_APROB = Carbon::now();
                $documento->APROBADO = 'S';
                $resultado = $documento->save();

                \Log::info('Documento guardado', [
                    'resultado' => $resultado,
                    'documento_id' => $documento->DOCUMENTO_INV,
                    'USUARIO_APRO' => $documento->USUARIO_APRO,
                    'FECHA_HORA_APROB' => $documento->FECHA_HORA_APROB,
                    'APROBADO' => $documento->APROBADO
                ]);
            });

            // Verificar después del guardado
            $documentoVerificado = Inventory::find($documentoId);
            \Log::info('Documento verificado después de guardar', [
                'USUARIO_APRO' => $documentoVerificado->USUARIO_APRO,
                'FECHA_HORA_APROB' => $documentoVerificado->FECHA_HORA_APROB,
                'APROBADO' => $documentoVerificado->APROBADO
            ]);

            Alert::success('Éxito', 'El documento ha sido aprobado correctamente.');

            return response()->json([
                'success' => true,
                'message' => 'Documento aprobado correctamente.',
                'data' => [
                    'documento_id' => $documentoId,
                    'usuario_apro' => $documentoVerificado->USUARIO_APRO,
                    'fecha_aprob' => $documentoVerificado->FECHA_HORA_APROB,
                    'aprobado' => $documentoVerificado->APROBADO
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al aprobar documento: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
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
            $documento = Inventory::find($documentoId);

            if (!$documento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Documento no encontrado.'
                ]);
            }

            // Verificar si no está aprobado
            if ($documento->APROBADO !== 'S') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este documento no está aprobado.'
                ]);
            }

            // Usar transacción
            DB::transaction(function () use ($documento) {
                $documento->USUARIO_APRO = null;
                $documento->FECHA_HORA_APROB = null;
                $documento->APROBADO = null;
                $documento->save();
            });

            Alert::success('Éxito', 'El documento ha sido desaprobado correctamente.');

            return response()->json([
                'success' => true,
                'message' => 'Documento desaprobado correctamente.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al desaprobar documento: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al desaprobar el documento: ' . $e->getMessage()
            ]);
        }
    }
}
