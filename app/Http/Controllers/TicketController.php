<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Dependencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Listar todos los tickets (solo para admin y encargado)
     */
    public function index()
    {
        $user = Auth::user();
        
        $tickets = Ticket::with(['solicitante', 'dependencia', 'asignadoA'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Listar mis tickets (para funcionario)
     */
    public function misTickets()
    {
        $user = Auth::user();
        
        $tickets = Ticket::with(['dependencia', 'asignadoA'])
            ->misTickets($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tickets.mis-tickets', compact('tickets'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $tiposServicio = Ticket::tiposServicio();
        $prioridades = Ticket::prioridades();
        
        return view('tickets.create', compact('tiposServicio', 'prioridades'));
    }

    /**
     * Guardar nuevo ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asunto' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo_servicio' => 'required|in:mantenimiento,asesoramiento,reparacion,configuracion,otro',
            'prioridad' => 'required|in:baja,media,alta,urgente',
        ], [
            'asunto.required' => 'El asunto es obligatorio',
            'descripcion.required' => 'La descripción es obligatoria',
            'tipo_servicio.required' => 'El tipo de servicio es obligatorio',
            'prioridad.required' => 'La prioridad es obligatoria',
        ]);

        $user = Auth::user();

        $ticket = Ticket::create([
            'codigo' => Ticket::generarCodigo(),
            'solicitante_id' => $user->id,
            'dependencia_id' => $user->dependencia_id,
            'unidad_academica_id' => $user->unidad_academica_id,
            'asunto' => $validated['asunto'],
            'descripcion' => $validated['descripcion'],
            'tipo_servicio' => $validated['tipo_servicio'],
            'prioridad' => $validated['prioridad'],
            'estado' => 'pendiente',
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket creado exitosamente. Código: ' . $ticket->codigo);
    }

    /**
     * Mostrar detalle del ticket
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['solicitante', 'dependencia', 'unidadAcademica', 'asignadoA']);
        
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user->hasPermission('tickets.view-all') && !$ticket->esSolicitante($user->id)) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }

        // Usuarios disponibles para asignar (solo encargados y admin)
        $usuariosParaAsignar = User::whereHas('roles', function($query) {
            $query->whereIn('slug', ['admin', 'encargado-lab']);
        })->get();

        return view('tickets.show', compact('ticket', 'usuariosParaAsignar'));
    }

    /**
     * Asignar ticket a un usuario
     */
    public function asignar(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'asignado_a' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'asignado_a' => $validated['asignado_a'],
            'estado' => 'en_proceso',
            'fecha_asignacion' => now(),
        ]);

        return back()->with('success', 'Ticket asignado correctamente.');
    }

    /**
     * Marcar ticket como listo (solo encargado)
     */
    public function marcarListo(Request $request, Ticket $ticket)
    {
        if (!$ticket->puedeSerMarcadoComoListo()) {
            return back()->with('error', 'El ticket no puede ser marcado como listo en su estado actual.');
        }

        $validated = $request->validate([
            'solucion' => 'required|string',
            'observaciones' => 'nullable|string',
        ], [
            'solucion.required' => 'Debes describir la solución aplicada',
        ]);

        $ticket->update([
            'estado' => 'listo',
            'solucion' => $validated['solucion'],
            'observaciones' => $validated['observaciones'] ?? null,
            'fecha_listo' => now(),
        ]);

        return back()->with('success', 'Ticket marcado como listo. Esperando confirmación del solicitante.');
    }

    /**
     * Finalizar ticket (solo solicitante)
     */
    public function finalizar(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        if (!$ticket->esSolicitante($user->id)) {
            return back()->with('error', 'Solo el solicitante puede finalizar el ticket.');
        }

        if (!$ticket->puedeSerFinalizado()) {
            return back()->with('error', 'El ticket debe estar en estado "Listo" para ser finalizado.');
        }

        $ticket->update([
            'estado' => 'finalizado',
            'fecha_finalizado' => now(),
        ]);

        return back()->with('success', 'Ticket finalizado correctamente. ¡Gracias por confirmar!');
    }

    /**
     * Cancelar ticket
     */
    public function cancelar(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        // Solo el solicitante o admin pueden cancelar
        if (!$ticket->esSolicitante($user->id) && !$user->hasRole('admin')) {
            return back()->with('error', 'No tienes permiso para cancelar este ticket.');
        }

        $validated = $request->validate([
            'motivo' => 'required|string',
        ], [
            'motivo.required' => 'Debes indicar el motivo de cancelación',
        ]);

        $ticket->update([
            'estado' => 'cancelado',
            'observaciones' => 'Cancelado: ' . $validated['motivo'],
        ]);

        return back()->with('success', 'Ticket cancelado.');
    }

    /**
     * Agregar observación al ticket
     */
    public function agregarObservacion(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'observacion' => 'required|string',
        ]);

        $observacionAnterior = $ticket->observaciones ?? '';
        $nuevaObservacion = '[' . now()->format('d/m/Y H:i') . ' - ' . Auth::user()->nombre_completo . '] ' . $validated['observacion'];
        
        $ticket->update([
            'observaciones' => $observacionAnterior . "\n" . $nuevaObservacion,
        ]);

        return back()->with('success', 'Observación agregada correctamente.');
    }
}