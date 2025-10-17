<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TicketController;

// Rutas públicas
Route::get('/', function () {
    return redirect('/login');
});

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        
        // Ver mis tickets (todos los usuarios autenticados)
        Route::get('/mis-tickets', [TicketController::class, 'misTickets'])
            ->name('mis-tickets');
        
        // Crear ticket (requiere permiso)
        Route::get('/crear', [TicketController::class, 'create'])
            ->middleware('permission:tickets.create')
            ->name('create');
        
        Route::post('/', [TicketController::class, 'store'])
            ->middleware('permission:tickets.create')
            ->name('store');
        
        // Ver todos los tickets (solo admin y encargado)
        Route::get('/', [TicketController::class, 'index'])
            ->middleware('permission:tickets.view-all')
            ->name('index');
        
        // Ver detalle de ticket
        Route::get('/{ticket}', [TicketController::class, 'show'])
            ->name('show');
        
        // Asignar ticket
        Route::post('/{ticket}/asignar', [TicketController::class, 'asignar'])
            ->middleware('permission:tickets.view-all')
            ->name('asignar');
        
        // Marcar como listo (encargado)
        Route::post('/{ticket}/marcar-listo', [TicketController::class, 'marcarListo'])
            ->middleware('permission:tickets.mark-ready')
            ->name('marcar-listo');
        
        // Finalizar ticket (solicitante)
        Route::post('/{ticket}/finalizar', [TicketController::class, 'finalizar'])
            ->middleware('permission:tickets.close')
            ->name('finalizar');
        
        // Cancelar ticket
        Route::post('/{ticket}/cancelar', [TicketController::class, 'cancelar'])
            ->name('cancelar');
        
        // Agregar observación
        Route::post('/{ticket}/observacion', [TicketController::class, 'agregarObservacion'])
            ->name('observacion');
    });
});