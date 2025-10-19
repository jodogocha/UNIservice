<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnidadAcademicaController;
use App\Http\Controllers\DependenciaController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ReportController;

// Ruta raíz - redirige al login si no está autenticado
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Rutas de autenticación (solo accesibles si NO está autenticado)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout (solo para autenticados)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas protegidas - TODAS requieren autenticación
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Auditoría (solo admin)
    Route::middleware(['auth', 'permission:audit.view'])->group(function () {
        Route::get('/auditoria', [AuditLogController::class, 'index'])->name('audit.index');
        Route::get('/auditoria/{log}', [AuditLogController::class, 'show'])->name('audit.show');
        Route::get('/auditoria/exportar/csv', [AuditLogController::class, 'export'])->name('audit.export');
        Route::post('/auditoria/limpiar', [AuditLogController::class, 'clean'])
            ->name('audit.clean')
            ->middleware('permission:audit.manage');
    });
    
    // Gestión de Unidades Académicas
    Route::middleware('permission:users.view')->group(function () {
        Route::resource('unidades-academicas', UnidadAcademicaController::class);
        Route::post('unidades-academicas/{unidadesAcademica}/cambiar-estado', [UnidadAcademicaController::class, 'cambiarEstado'])
            ->name('unidades-academicas.cambiar-estado')
            ->middleware('permission:users.edit');
    });

    // Gestión de Dependencias
    Route::middleware('permission:users.view')->group(function () {
        Route::resource('dependencias', DependenciaController::class);
        Route::post('dependencias/{dependencia}/cambiar-estado', [DependenciaController::class, 'cambiarEstado'])
            ->name('dependencias.cambiar-estado')
            ->middleware('permission:users.edit');
    });

    // Gestión de Roles
    Route::middleware('permission:users.view')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Módulo de Reportes
    Route::prefix('reportes')->middleware('permission:reports.view')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reportes.index');
        Route::get('/trabajos-por-usuario', [ReportController::class, 'trabajosPorUsuario'])->name('reportes.trabajos-usuario');
        Route::get('/solicitudes-por-dependencia', [ReportController::class, 'solicitudesPorDependencia'])->name('reportes.solicitudes-dependencia');
        Route::get('/ranking-dependencias', [ReportController::class, 'rankingDependencias'])->name('reportes.ranking-dependencias');
        Route::get('/ranking-usuarios', [ReportController::class, 'rankingUsuarios'])->name('reportes.ranking-usuarios');
        Route::get('/servicios-por-horario', [ReportController::class, 'serviciosPorHorario'])->name('reportes.servicios-horario');
        Route::get('/trabajos-asignados', [ReportController::class, 'trabajosAsignados'])->name('reportes.trabajos-asignados');
        Route::get('/totales-mensuales', [ReportController::class, 'totalesMensuales'])->name('reportes.totales-mensuales');
        Route::get('/totales-anuales', [ReportController::class, 'totalesAnuales'])->name('reportes.totales-anuales');
        
        // Exportar PDF
        Route::get('/exportar/{tipo}', [ReportController::class, 'exportarPDF'])->name('reportes.exportar-pdf')
            ->middleware('permission:reports.export');
    });
    
    // Gestión de Usuarios
    Route::middleware('permission:users.view')->group(function () {
        Route::resource('usuarios', UserController::class);
        
        // Ruta para cambiar estado del usuario
        Route::post('usuarios/{usuario}/cambiar-estado', [UserController::class, 'cambiarEstado'])
            ->name('usuarios.cambiar-estado')
            ->middleware('permission:users.edit');
        
        // Ruta AJAX para obtener dependencias
        Route::get('api/dependencias/{unidadAcademica}', [UserController::class, 'getDependencias'])
            ->name('api.dependencias');
    });

    // ==================== RUTAS DE TICKETS ====================
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
        
        // Editar ticket
        Route::get('/{ticket}/editar', [TicketController::class, 'edit'])
            ->middleware('permission:tickets.edit')
            ->name('edit');
        
        Route::put('/{ticket}', [TicketController::class, 'update'])
            ->middleware('permission:tickets.edit')
            ->name('update');
        
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
        
        // Eliminar ticket
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])
            ->middleware('permission:tickets.delete')
            ->name('destroy');
    });
});