<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Dependencia;
use App\Models\UnidadAcademica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Dashboard principal de reportes
     */
    public function index()
    {
        $user = auth()->user();
        
        // Estadísticas generales
        $stats = [
            'total_tickets' => Ticket::count(),
            'tickets_mes_actual' => Ticket::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'tickets_resueltos' => Ticket::whereIn('estado', ['finalizado'])->count(),
            'tickets_pendientes' => Ticket::whereIn('estado', ['pendiente', 'en_proceso'])->count(),
        ];

        return view('reportes.index', compact('stats'));
    }

    /**
     * Reporte mensual de trabajos realizados por usuario
     */
    public function trabajosPorUsuario(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);
        
        $tecnicos = User::whereHas('roles', function($q) {
            $q->whereIn('slug', ['admin', 'encargado-lab']);
        })->where('activo', true)->get();

        $trabajosRealizados = [];
        foreach ($tecnicos as $tecnico) {
            $trabajosRealizados[] = [
                'tecnico' => $tecnico->nombre_completo,
                'total' => Ticket::where('asignado_a', $tecnico->id)
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->count(),
                'finalizados' => Ticket::where('asignado_a', $tecnico->id)
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->where('estado', 'finalizado')
                    ->count(),
                'en_proceso' => Ticket::where('asignado_a', $tecnico->id)
                    ->whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->where('estado', 'en_proceso')
                    ->count(),
            ];
        }

        return view('reportes.trabajos-por-usuario', compact('trabajosRealizados', 'mes', 'anio', 'tecnicos'));
    }

    /**
     * Reporte de solicitudes por dependencia
     */
    public function solicitudesPorDependencia(Request $request)
    {
        $mes = $request->get('mes');
        $anio = $request->get('anio');
        
        $query = Ticket::select('dependencia_id', DB::raw('count(*) as total'))
            ->with('dependencia')
            ->groupBy('dependencia_id')
            ->orderByDesc('total');

        if ($mes && $anio) {
            $query->whereMonth('created_at', $mes)
                  ->whereYear('created_at', $anio);
        }

        $solicitudes = $query->get();

        return view('reportes.solicitudes-por-dependencia', compact('solicitudes', 'mes', 'anio'));
    }

    /**
     * Ranking de dependencias que más solicitan
     */
    public function rankingDependencias(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth()->format('Y-m-d'));

        $ranking = Ticket::select('dependencia_id', DB::raw('count(*) as total_solicitudes'))
            ->with('dependencia')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy('dependencia_id')
            ->orderByDesc('total_solicitudes')
            ->limit(10)
            ->get();

        return view('reportes.ranking-dependencias', compact('ranking', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Ranking de usuarios que más solicitan
     */
    public function rankingUsuarios(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth()->format('Y-m-d'));

        $ranking = Ticket::select('solicitante_id', DB::raw('count(*) as total_solicitudes'))
            ->with('solicitante.dependencia')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy('solicitante_id')
            ->orderByDesc('total_solicitudes')
            ->limit(10)
            ->get();

        return view('reportes.ranking-usuarios', compact('ranking', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Reporte de servicios por franja horaria
     */
    public function serviciosPorHorario(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth()->format('Y-m-d'));
        $horaInicio = $request->get('hora_inicio', '00:00');
        $horaFin = $request->get('hora_fin', '23:59');

        $tickets = Ticket::whereBetween('created_at', [$fechaInicio . ' ' . $horaInicio, $fechaFin . ' ' . $horaFin])
            ->whereTime('created_at', '>=', $horaInicio)
            ->whereTime('created_at', '<=', $horaFin)
            ->with(['solicitante', 'dependencia', 'asignado'])
            ->get();

        $estadisticas = [
            'total' => $tickets->count(),
            'finalizados' => $tickets->where('estado', 'finalizado')->count(),
            'en_proceso' => $tickets->where('estado', 'en_proceso')->count(),
            'pendientes' => $tickets->where('estado', 'pendiente')->count(),
        ];

        // Agrupar por hora
        $ticketsPorHora = $tickets->groupBy(function($ticket) {
            return Carbon::parse($ticket->created_at)->format('H:00');
        })->map(function($group) {
            return $group->count();
        })->sortKeys();

        return view('reportes.servicios-por-horario', compact('tickets', 'estadisticas', 'ticketsPorHora', 'fechaInicio', 'fechaFin', 'horaInicio', 'horaFin'));
    }

    /**
     * Reporte de trabajos asignados por usuario
     */
    public function trabajosAsignados(Request $request)
    {
        $usuarioId = $request->get('usuario_id');
        $estado = $request->get('estado');

        $tecnicos = User::whereHas('roles', function($q) {
            $q->whereIn('slug', ['admin', 'encargado-lab']);
        })->where('activo', true)->get();

        $query = Ticket::with(['solicitante', 'dependencia']);

        if ($usuarioId) {
            $query->where('asignado_a', $usuarioId);
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20)->appends(request()->query());

        // Estadísticas del usuario seleccionado
        $estadisticasUsuario = null;
        if ($usuarioId) {
            $usuario = User::find($usuarioId);
            $estadisticasUsuario = [
                'usuario' => $usuario,
                'total_asignados' => Ticket::where('asignado_a', $usuarioId)->count(),
                'pendientes' => Ticket::where('asignado_a', $usuarioId)->where('estado', 'pendiente')->count(),
                'en_proceso' => Ticket::where('asignado_a', $usuarioId)->where('estado', 'en_proceso')->count(),
                'finalizados' => Ticket::where('asignado_a', $usuarioId)->where('estado', 'finalizado')->count(),
            ];
        }

        return view('reportes.trabajos-asignados', compact('tickets', 'tecnicos', 'estadisticasUsuario', 'usuarioId', 'estado'));
    }

    /**
     * Reporte de totales mensuales
     */
    public function totalesMensuales(Request $request)
    {
        $anio = $request->get('anio', now()->year);

        $ticketsPorMes = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $ticketsPorMes[$mes] = [
                'mes' => Carbon::create($anio, $mes, 1)->locale('es')->monthName,
                'total' => Ticket::whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->count(),
                'finalizados' => Ticket::whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->where('estado', 'finalizado')
                    ->count(),
                'pendientes' => Ticket::whereMonth('created_at', $mes)
                    ->whereYear('created_at', $anio)
                    ->whereIn('estado', ['pendiente', 'en_proceso'])
                    ->count(),
            ];
        }

        // Totales por tipo de servicio
        $totalesPorTipo = Ticket::select('tipo_servicio', DB::raw('count(*) as total'))
            ->whereYear('created_at', $anio)
            ->groupBy('tipo_servicio')
            ->get();

        // Totales por prioridad
        $totalesPorPrioridad = Ticket::select('prioridad', DB::raw('count(*) as total'))
            ->whereYear('created_at', $anio)
            ->groupBy('prioridad')
            ->get();

        return view('reportes.totales-mensuales', compact('ticketsPorMes', 'anio', 'totalesPorTipo', 'totalesPorPrioridad'));
    }

    /**
     * Reporte de totales anuales
     */
    public function totalesAnuales(Request $request)
    {
        $anioActual = now()->year;
        $aniosDisponibles = range($anioActual - 5, $anioActual);

        $ticketsPorAnio = [];
        foreach ($aniosDisponibles as $anio) {
            $ticketsPorAnio[$anio] = [
                'anio' => $anio,
                'total' => Ticket::whereYear('created_at', $anio)->count(),
                'finalizados' => Ticket::whereYear('created_at', $anio)->where('estado', 'finalizado')->count(),
                'pendientes' => Ticket::whereYear('created_at', $anio)->whereIn('estado', ['pendiente', 'en_proceso'])->count(),
            ];
        }

        return view('reportes.totales-anuales', compact('ticketsPorAnio', 'aniosDisponibles'));
    }

    /**
     * Exportar reporte a PDF
     */
    public function exportarPDF($tipo, Request $request)
    {
        $data = [];
        $vista = '';

        switch ($tipo) {
            case 'trabajos-usuario':
                $data = $this->obtenerDatosTrabajosUsuario($request);
                $vista = 'reportes.pdf.trabajos-usuario';
                break;
            case 'solicitudes-dependencia':
                $data = $this->obtenerDatosSolicitudesDependencia($request);
                $vista = 'reportes.pdf.solicitudes-dependencia';
                break;
            case 'totales-mensuales':
                $data = $this->obtenerDatosTotalesMensuales($request);
                $vista = 'reportes.pdf.totales-mensuales';
                break;
        }

        $pdf = PDF::loadView($vista, $data);
        return $pdf->download('reporte-' . $tipo . '-' . now()->format('Y-m-d') . '.pdf');
    }

    private function obtenerDatosTrabajosUsuario($request)
    {
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);
        
        // Implementar lógica similar a trabajosPorUsuario
        return compact('mes', 'anio');
    }

    private function obtenerDatosSolicitudesDependencia($request)
    {
        // Implementar lógica similar a solicitudesPorDependencia
        return [];
    }

    private function obtenerDatosTotalesMensuales($request)
    {
        // Implementar lógica similar a totalesMensuales
        return [];
    }
}