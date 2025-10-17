<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Estadísticas según el rol
        if ($user->hasPermission('tickets.view-all')) {
            // Admin o Encargado: estadísticas globales
            $ticketsPendientes = Ticket::where('estado', 'pendiente')->count();
            $ticketsEnProceso = Ticket::where('estado', 'en_proceso')->count();
            $ticketsListos = Ticket::where('estado', 'listo')->count();
            $ticketsFinalizados = Ticket::where('estado', 'finalizado')->count();
            $usuariosActivos = User::where('activo', true)->count();
            
            $ticketsRecientes = Ticket::with(['solicitante', 'dependencia'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            // Funcionario: solo sus tickets
            $ticketsPendientes = Ticket::where('solicitante_id', $user->id)->where('estado', 'pendiente')->count();
            $ticketsEnProceso = Ticket::where('solicitante_id', $user->id)->where('estado', 'en_proceso')->count();
            $ticketsListos = Ticket::where('solicitante_id', $user->id)->where('estado', 'listo')->count();
            $ticketsFinalizados = Ticket::where('solicitante_id', $user->id)->where('estado', 'finalizado')->count();
            $usuariosActivos = 0;
            
            $ticketsRecientes = Ticket::with(['dependencia', 'asignadoA'])
                ->where('solicitante_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
        
        return view('home', compact(
            'user',
            'ticketsPendientes',
            'ticketsEnProceso',
            'ticketsListos',
            'ticketsFinalizados',
            'usuariosActivos',
            'ticketsRecientes'
        ));
    }
}