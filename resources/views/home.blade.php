@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1><i class="fas fa-tachometer-alt"></i> Estadísticas</h1>
@stop

@section('content')
    {{-- Estadísticas de Tickets --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $ticketsPendientes }}</h3>
                    <p>Tickets Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('tickets.index', ['estado' => 'pendiente']) }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $ticketsEnProceso }}</h3>
                    <p>En Proceso</p>
                </div>
                <div class="icon">
                    <i class="fas fa-sync"></i>
                </div>
                <a href="{{ route('tickets.index', ['estado' => 'en_proceso']) }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $ticketsListos }}</h3>
                    <p>Listos para Retiro</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
                <a href="{{ route('tickets.index', ['estado' => 'listo']) }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $ticketsFinalizados }}</h3>
                    <p>Finalizados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <a href="{{ route('tickets.index', ['estado' => 'finalizado']) }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Mis Tickets / Tickets Recientes --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-ticket-alt"></i> 
                        @if($user->hasPermission('tickets.view-all'))
                            Tickets Recientes
                        @else
                            Mis Tickets Recientes
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('tickets.mis-tickets') }}" class="btn btn-sm btn-light">
                            Ver todos
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($user->hasPermission('tickets.view-all'))
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Solicitante</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ticketsRecientes as $ticket)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tickets.show', $ticket) }}">
                                                {{ $ticket->codigo }}
                                            </a>
                                        </td>
                                        <td>{{ $ticket->solicitante->nombre_completo }}</td>
                                        <td>
                                            <span class="badge {{ $ticket->estado_badge }}">
                                                {{ $ticket->estado_nombre }}
                                            </span>
                                        </td>
                                        <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay tickets recientes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Asunto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($misTickets as $ticket)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tickets.show', $ticket) }}">
                                                {{ $ticket->codigo }}
                                            </a>
                                        </td>
                                        <td>{{ Str::limit($ticket->asunto, 30) }}</td>
                                        <td>
                                            <span class="badge {{ $ticket->estado_badge }}">
                                                {{ $ticket->estado_nombre }}
                                            </span>
                                        </td>
                                        <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <p class="py-3">No tienes tickets registrados</p>
                                            <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i> Crear mi primer ticket
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tickets Asignados a Mí --}}
        @if($ticketsAsignados->count() > 0)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">
                            <i class="fas fa-user-clock"></i> Tickets Asignados a Mí
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Solicitante</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ticketsAsignados as $ticket)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tickets.show', $ticket) }}">
                                                {{ $ticket->codigo }}
                                            </a>
                                        </td>
                                        <td>{{ $ticket->solicitante->nombre_completo }}</td>
                                        <td>
                                            <span class="badge {{ $ticket->estado_badge }}">
                                                {{ $ticket->estado_nombre }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-xs btn-primary">
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Estadísticas Adicionales (Admin/Encargados) --}}
        @if($user->hasPermission('users.view'))
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie"></i> Tickets por Prioridad
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="progress-group">
                            <span class="progress-text">Urgente</span>
                            <span class="float-right"><b>{{ $ticketsPorPrioridad['urgente'] ?? 0 }}</b></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-danger" style="width: {{ $totalTickets > 0 ? ($ticketsPorPrioridad['urgente'] ?? 0) / $totalTickets * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="progress-group">
                            <span class="progress-text">Alta</span>
                            <span class="float-right"><b>{{ $ticketsPorPrioridad['alta'] ?? 0 }}</b></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" style="width: {{ $totalTickets > 0 ? ($ticketsPorPrioridad['alta'] ?? 0) / $totalTickets * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="progress-group">
                            <span class="progress-text">Media</span>
                            <span class="float-right"><b>{{ $ticketsPorPrioridad['media'] ?? 0 }}</b></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-info" style="width: {{ $totalTickets > 0 ? ($ticketsPorPrioridad['media'] ?? 0) / $totalTickets * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="progress-group">
                            <span class="progress-text">Baja</span>
                            <span class="float-right"><b>{{ $ticketsPorPrioridad['baja'] ?? 0 }}</b></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-secondary" style="width: {{ $totalTickets > 0 ? ($ticketsPorPrioridad['baja'] ?? 0) / $totalTickets * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Accesos Rápidos --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i> Accesos Rápidos
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @can('tickets.create')
                            <div class="col-md-3">
                                <a href="{{ route('tickets.create') }}" class="btn btn-app btn-block">
                                    <i class="fas fa-plus"></i> Nuevo Ticket
                                </a>
                            </div>
                        @endcan
                        
                        <div class="col-md-3">
                            <a href="{{ route('tickets.mis-tickets') }}" class="btn btn-app btn-block">
                                <i class="fas fa-list"></i> Mis Tickets
                            </a>
                        </div>
                        
                        @can('tickets.view-all')
                            <div class="col-md-3">
                                <a href="{{ route('tickets.index') }}" class="btn btn-app btn-block">
                                    <i class="fas fa-list-alt"></i> Todos los Tickets
                                </a>
                            </div>
                        @endcan
                        
                        @can('users.view')
                            <div class="col-md-3">
                                <a href="{{ route('usuarios.index') }}" class="btn btn-app btn-block">
                                    <i class="fas fa-users"></i> Usuarios
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
    console.log('Dashboard cargado correctamente');
</script>
@stop