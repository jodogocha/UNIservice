<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Determinar qué tickets puede ver el usuario
        $ticketsQuery = Ticket::query();
        
        // Si NO es admin, filtrar por unidad académica
        if (!$user->hasRole('admin')) {
            if ($user->hasPermission('tickets.view-all')) {
                // Encargado o técnico: solo de su unidad académica
                $ticketsQuery->where('unidad_academica_id', $user->unidad_academica_id);
            } else {
                // Usuario común: solo sus tickets
                $ticketsQuery->where('solicitante_id', $user->id);
            }
        }
        
        // Contadores de tickets por estado
        $ticketsPendientes = (clone $ticketsQuery)->where('estado', 'pendiente')->count();
        $ticketsEnProceso = (clone $ticketsQuery)->where('estado', 'en_proceso')->count();
        $ticketsListos = (clone $ticketsQuery)->where('estado', 'listo')->count();
        $ticketsFinalizados = (clone $ticketsQuery)->where('estado', 'finalizado')->count();
        $ticketsCancelados = (clone $ticketsQuery)->where('estado', 'cancelado')->count();
        
        // Total de tickets
        $totalTickets = $ticketsQuery->count();
        
        // Tickets recientes (últimos 5)
        $ticketsRecientes = (clone $ticketsQuery)
            ->with(['solicitante', 'dependencia'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Mis tickets (si es usuario común)
        $misTickets = Ticket::where('solicitante_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Tickets asignados a mí (si es técnico o encargado)
        $ticketsAsignados = Ticket::where('asignado_a', $user->id)
            ->whereIn('estado', ['en_proceso', 'pendiente'])
            ->with(['solicitante', 'dependencia'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Estadísticas adicionales (solo para admin y encargados)
        $usuariosActivos = 0;
        $ticketsPorPrioridad = [];
        
        if ($user->hasPermission('users.view')) {
            $usuariosActivos = User::where('activo', true)->count();
            
            // Tickets por prioridad
            $ticketsPorPrioridad = [
                'urgente' => (clone $ticketsQuery)->where('prioridad', 'urgente')->count(),
                'alta' => (clone $ticketsQuery)->where('prioridad', 'alta')->count(),
                'media' => (clone $ticketsQuery)->where('prioridad', 'media')->count(),
                'baja' => (clone $ticketsQuery)->where('prioridad', 'baja')->count(),
            ];
        }
        
        return view('home', compact(
            'user',
            'ticketsPendientes',
            'ticketsEnProceso',
            'ticketsListos',
            'ticketsFinalizados',
            'ticketsCancelados',
            'totalTickets',
            'usuariosActivos',
            'ticketsRecientes',
            'misTickets',
            'ticketsAsignados',
            'ticketsPorPrioridad'
        ));
    }
}