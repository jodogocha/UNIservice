<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Listar todos los tickets (solo para admin y encargado)
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['solicitante', 'asignado', 'dependencia', 'unidadAcademica']);

        // Filtro por estado
        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        // Filtro por prioridad
        if ($request->has('prioridad') && $request->prioridad !== '') {
            $query->where('prioridad', $request->prioridad);
        }

        // Filtro por tipo de servicio
        if ($request->has('tipo_servicio') && $request->tipo_servicio !== '') {
            $query->where('tipo_servicio', $request->tipo_servicio);
        }

        // Búsqueda por código o asunto
        if ($request->has('buscar') && $request->buscar !== '') {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo', 'LIKE', "%{$buscar}%")
                  ->orWhere('asunto', 'LIKE', "%{$buscar}%");
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Listar mis tickets (para funcionario)
     */
    public function misTickets()
    {
        $tickets = Ticket::with(['dependencia', 'unidadAcademica', 'asignado'])
            ->where('solicitante_id', auth()->id())
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
            'tipo_servicio' => 'required|in:reparacion,mantenimiento,instalacion,consulta,otro',
            'prioridad' => 'required|in:baja,media,alta,urgente',
        ], [
            'asunto.required' => 'El asunto es obligatorio',
            'descripcion.required' => 'La descripción es obligatoria',
            'tipo_servicio.required' => 'El tipo de servicio es obligatorio',
            'prioridad.required' => 'La prioridad es obligatoria',
        ]);

        try {
            $user = Auth::user();

            $ticket = Ticket::create([
                'asunto' => $validated['asunto'],
                'descripcion' => $validated['descripcion'],
                'tipo_servicio' => $validated['tipo_servicio'],
                'prioridad' => $validated['prioridad'],
                'solicitante_id' => $user->id,
                'dependencia_id' => $user->dependencia_id,
                'unidad_academica_id' => $user->unidad_academica_id,
                'estado' => 'pendiente',
            ]);

            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', 'Ticket creado exitosamente. Código: ' . $ticket->codigo);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear el ticket: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle del ticket
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['solicitante', 'dependencia', 'unidadAcademica', 'asignado']);
        
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user->hasPermission('tickets.view-all') && $ticket->solicitante_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }

        // Usuarios disponibles para asignar (solo encargados y admin)
        $usuariosParaAsignar = User::whereHas('roles', function($query) {
            $query->whereIn('slug', ['admin', 'encargado-lab']);
        })->where('activo', true)->get();

        return view('tickets.show', compact('ticket', 'usuariosParaAsignar'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Ticket $ticket)
    {
        $tiposServicio = Ticket::tiposServicio();
        $prioridades = Ticket::prioridades();
        $estados = Ticket::estados();
        
        // Usuarios disponibles para asignar
        $usuariosParaAsignar = User::whereHas('roles', function($query) {
            $query->whereIn('slug', ['admin', 'encargado-lab']);
        })->where('activo', true)->get();

        return view('tickets.edit', compact('ticket', 'tiposServicio', 'prioridades', 'estados', 'usuariosParaAsignar'));
    }

    /**
     * Actualizar ticket
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'asunto' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo_servicio' => 'required|in:reparacion,mantenimiento,instalacion,consulta,otro',
            'prioridad' => 'required|in:baja,media,alta,urgente',
            'estado' => 'required|in:pendiente,en_proceso,listo,finalizado,cancelado',
            'asignado_a' => 'nullable|exists:users,id',
            'observaciones' => 'nullable|string',
            'solucion' => 'nullable|string',
        ]);

        try {
            $dataToUpdate = [
                'asunto' => $validated['asunto'],
                'descripcion' => $validated['descripcion'],
                'tipo_servicio' => $validated['tipo_servicio'],
                'prioridad' => $validated['prioridad'],
                'estado' => $validated['estado'],
                'observaciones' => $validated['observaciones'] ?? null,
                'solucion' => $validated['solucion'] ?? null,
            ];

            // Si se asigna a alguien por primera vez
            if (!empty($validated['asignado_a']) && empty($ticket->asignado_a)) {
                $dataToUpdate['asignado_a'] = $validated['asignado_a'];
                $dataToUpdate['fecha_asignacion'] = now();
                if ($ticket->estado === 'pendiente') {
                    $dataToUpdate['estado'] = 'en_proceso';
                }
            } elseif (!empty($validated['asignado_a'])) {
                $dataToUpdate['asignado_a'] = $validated['asignado_a'];
            }

            $ticket->update($dataToUpdate);

            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', 'Ticket actualizado exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el ticket: ' . $e->getMessage());
        }
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
        if ($ticket->estado !== 'en_proceso') {
            return back()->with('error', 'El ticket debe estar en proceso para marcarlo como listo.');
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
            'observaciones' => $validated['observaciones'] ?? $ticket->observaciones,
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

        if ($ticket->solicitante_id !== $user->id) {
            return back()->with('error', 'Solo el solicitante puede finalizar el ticket.');
        }

        if ($ticket->estado !== 'listo') {
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
        if ($ticket->solicitante_id !== $user->id && !$user->hasPermission('tickets.delete')) {
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

    /**
     * Eliminar ticket
     */
    public function destroy(Ticket $ticket)
    {
        try {
            $ticket->delete();

            return redirect()
                ->route('tickets.index')
                ->with('success', 'Ticket eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el ticket: ' . $e->getMessage());
        }
    }
}