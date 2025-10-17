@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('title', 'Mis Tickets')

@section('content_header')
    <h1><i class="fas fa-ticket-alt"></i> Mis Tickets</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-12">
            @can('tickets.create')
                <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Nuevo Ticket
                </a>
            @endcan
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Mis Solicitudes de Servicio</h3>
            <div class="card-tools">
                <span class="badge badge-primary">Total: {{ $tickets->total() }}</span>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Asunto</th>
                        <th>Tipo</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Asignado a</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td><strong>{{ $ticket->codigo }}</strong></td>
                            <td>{{ Str::limit($ticket->asunto, 40) }}</td>
                            <td><span class="badge badge-info">{{ Ticket::tiposServicio()[$ticket->tipo_servicio] }}</span></td>
                            <td><span class="badge {{ $ticket->prioridad_badge }}">{{ Ticket::prioridades()[$ticket->prioridad] }}</span></td>
                            <td><span class="badge {{ $ticket->estado_badge }}">{{ Ticket::estados()[$ticket->estado] }}</span></td>
                            <td>{{ $ticket->asignadoA->nombre_completo ?? 'Sin asignar' }}</td>
                            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <p class="text-muted mt-3">No has creado ningún ticket aún.</p>
                                @can('tickets.create')
                                    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Crear mi primer ticket
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $tickets->links() }}
        </div>
    </div>
@stop