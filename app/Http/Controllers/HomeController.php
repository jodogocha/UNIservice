<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Valores por defecto sin consultar la BD
        $ticketsPendientes = 0;
        $ticketsEnProceso = 0;
        $ticketsListos = 0;
        $ticketsFinalizados = 0;
        $usuariosActivos = 0;
        $ticketsRecientes = collect();
        
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