@extends('adminlte::page')

@section('title', 'Dashboard - UNIservice')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Bienvenido!</h5>
                <strong>{{ $user->nombre_completo }}</strong><br>
                <small>
                    Dependencia: {{ $user->dependencia->nombre ?? 'N/A' }}<br>
                    Rol: {{ $user->roles->first()->nombre ?? 'Sin rol asignado' }}
                </small>
            </div>
        </div>
    </div>

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
                @can('tickets.view-all')
                    <a href="{{ route('tickets.index') }}" class="small-box-footer">
                        Ver más <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a href="{{ route('tickets.mis-tickets') }}" class="small-box-footer">
                        Ver mis tickets <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @endcan
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $ticketsEnProceso }}</h3>
                    <p>En Proceso</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wrench"></i>
                </div>
                @can('tickets.view-all')
                    <a href="{{ route('tickets.index') }}" class="small-box-footer">
                        Ver más <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a href="{{ route('tickets.mis-tickets') }}" class="small-box-footer">
                        Ver mis tickets <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @endcan
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $ticketsListos }}</h3>
                    <p>Listos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                @can('tickets.view-all')
                    <a href="{{ route('tickets.index') }}" class="small-box-footer">
                        Ver más <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a href="{{ route('tickets.mis-tickets') }}" class="small-box-footer">
                        Ver mis tickets <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @endcan
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
                @can('tickets.view-all')
                    <a href="{{ route('tickets.index') }}" class="small-box-footer">
                        Ver más <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a href="{{ route('tickets.mis-tickets') }}" class="small-box-footer">
                        Ver mis tickets <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        @can('tickets.view-all')
                            Tickets Recientes
                        @else
                            Mis Tickets Recientes
                        @endcan
                    </h3>
                    <div class="card-tools">
                        @can('tickets.create')
                            <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Nuevo Ticket
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Asunto</th>
                                @can('tickets.view-all')
                                    <th>Solicitante</th>
                                @endcan
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ticketsRecientes as $ticket)
                                <tr>
                                    <td><strong>{{ $ticket->codigo }}</strong></td>
                                    <td>{{ Str::limit($ticket->asunto, 40) }}</td>
                                    @can('tickets.view-all')
                                        <td>{{ $ticket->solicitante->nombre_completo }}</td>
                                    @endcan
                                    <td><span class="badge {{ $ticket->estado_badge }}">{{ \App\Models\Ticket::estados()[$ticket->estado] }}</span></td>
                                    <td><span class="badge {{ $ticket->prioridad_badge }}">{{ \App\Models\Ticket::prioridades()[$ticket->prioridad] }}</span></td>
                                    <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay tickets recientes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    @can('tickets.view-all')
                        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-default">Ver todos los tickets</a>
                    @else
                        <a href="{{ route('tickets.mis-tickets') }}" class="btn btn-sm btn-default">Ver todos mis tickets</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .small-box .icon {
            font-size: 70px;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('UNIservice - Dashboard cargado correctamente');
    </script>
@stop