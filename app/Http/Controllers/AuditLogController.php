<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Mostrar el listado de logs de auditoría
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por acción
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filtro por módulo
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filtro por fecha desde
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        // Filtro por fecha hasta
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Búsqueda por descripción
        if ($request->filled('buscar')) {
            $query->where('description', 'LIKE', '%'.$request->buscar.'%');
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->appends(request()->query());

        // Obtener usuarios para el filtro
        $usuarios = User::where('activo', true)->orderBy('name')->get();

        // Obtener acciones únicas
        $acciones = AuditLog::select('action')->distinct()->pluck('action');

        // Obtener módulos únicos
        $modulos = AuditLog::select('module')->distinct()->pluck('module');

        return view('audit.index', compact('logs', 'usuarios', 'acciones', 'modulos'));
    }

    /**
     * Mostrar detalle de un log
     */
    public function show(AuditLog $log)
    {
        $log->load('user');

        return view('audit.show', compact('log'));
    }

    /**
     * Exportar logs a CSV
     */
    public function export(Request $request)
    {
        $query = AuditLog::with('user');

        // Aplicar los mismos filtros que en index
        if ($request->has('user_id') && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('action') && $request->action !== '') {
            $query->where('action', $request->action);
        }

        if ($request->has('module') && $request->module !== '') {
            $query->where('module', $request->module);
        }

        if ($request->has('fecha_desde') && $request->fecha_desde !== '') {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta') && $request->fecha_hasta !== '') {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'auditoria_'.date('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            fputcsv($file, [
                'ID',
                'Fecha',
                'Usuario',
                'Acción',
                'Módulo',
                'Descripción',
                'IP',
            ]);

            // Datos
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user_name,
                    $log->action_name,
                    $log->module,
                    $log->description,
                    $log->ip_address,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Limpiar logs antiguos
     */
    public function clean(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:30|max:365',
        ]);

        $date = Carbon::now()->subDays($validated['days']);
        $deleted = AuditLog::where('created_at', '<', $date)->delete();

        return back()->with('success', "Se eliminaron {$deleted} registros de auditoría anteriores a ".$date->format('d/m/Y'));
    }
}
