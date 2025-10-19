@extends('adminlte::page')

@section('title', 'Trabajos Asignados')

@section('content_header')
    <h1><i class="fas fa-tasks"></i> Trabajos Asignados por Usuario</h1>
@stop

@section('content')
    {{-- Filtros --}}
    <div class="card">
        <div class="card-header bg-secondary">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reportes.trabajos-asignados') }}" method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="usuario_id">Técnico:</label>
                            <select class="form-control" id="usuario_id" name="usuario_id">
                                <option value="">Todos los técnicos</option>
                                @foreach($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}" {{ $usuarioId == $tecnico->id ? 'selected' : '' }}>
                                        {{ $tecnico->nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="">Todos los estados</option>
                                @foreach(\App\Models\Ticket::estados() as $key => $value)
                                    <option value="{{ $key }}" {{ $estado == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-secondary btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Estadísticas del Usuario Seleccionado --}}
    @if($estadisticasUsuario)
        <div class="row">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg-info">
                        <h3 class="widget-user-username">{{ $estadisticasUsuario['usuario']->nombre_completo }}</h3>
                        <h5 class="widget-user-desc">
                            {{ $estadisticasUsuario['usuario']->email }} | 
                            {{ $estadisticasUsuario['usuario']->dependencia->nombre ?? 'N/A' }}
                        </h5>
                    </div>
                    <div class="widget-user-image">
                        <i class="fas fa-user-circle fa-5x text-white"></i>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $estadisticasUsuario['total_asignados'] }}</h5>
                                    <span class="description-text">TOTAL ASIGNADOS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header text-warning">{{ $estadisticasUsuario['pendientes'] }}</h5>
                                    <span class="description-text">PENDIENTES</span>
                                </div>
                            </div>
                            <div class="col-sm-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header text-primary">{{ $estadisticasUsuario['en_proceso'] }}</h5>
                                    <span class="description-text">EN PROCESO</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="description-block">
                                    <h5 class="description-header text-success">{{ $estadisticasUsuario['finalizados'] }}</h5>
                                    <span class="description-text">FINALIZADOS</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="progress-group">
                                    <span class="progress-text">Progreso de Finalización</span>
                                    <span class="float-right">
                                        <b>{{ $estadisticasUsuario['finalizados'] }}</b>/{{ $estadisticasUsuario['total_asignados'] }}
                                    </span>
                                    <div class="progress progress-sm">
                                        @php
                                            $porcentajeCompleto = $estadisticasUsuario['total_asignados'] > 0 
                                                ? round(($estadisticasUsuario['finalizados'] / $estadisticasUsuario['total_asignados']) * 100, 1) 
                                                : 0;
                                        @endphp
                                        <div class="progress-bar bg-success" style="width: {{ $porcentajeCompleto }}%"></div>
                                    </div>
                                    <span class="progress-number"><b>{{ $porcentajeCompleto }}%</b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Tabla de Tickets --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fas fa-list"></i> Listado de Trabajos</h3>
            <div class="card-tools">
                <span class="badge badge-light">Total: {{ $tickets->total() }}</span>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Asunto</th>
                        <th>Solicitante</th>
                        <th>Dependencia</th>
                        <th>Tipo</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td><strong>{{ $ticket->codigo }}</strong></td>
                            <td>{{ Str::limit($ticket->asunto, 30) }}</td>
                            <td>{{ $ticket->solicitante->nombre_completo }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $ticket->dependencia->codigo }}
                                </span>
                            </td>
                            <td>{{ $ticket->tipo_servicio_nombre }}</td>
                            <td>
                                <span class="badge {{ $ticket->prioridad_badge }}">
                                    {{ $ticket->prioridad_nombre }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $ticket->estado_badge }}">
                                    {{ $ticket->estado_nombre }}
                                </span>
                            </td>
                            <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" 
                                   class="btn btn-sm btn-info"
                                   target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No hay trabajos asignados con los filtros seleccionados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
            <div class="card-footer">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop