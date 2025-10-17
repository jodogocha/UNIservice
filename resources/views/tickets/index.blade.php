@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('title', 'Todos los Tickets')

@section('content_header')
    <h1><i class="fas fa-list-alt"></i> Todos los Tickets</h1>
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Gestión de Tickets de Servicio</h3>
            <div class="card-tools">
                <span class="badge badge-primary">Total: {{ $tickets->total() }}</span>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Asunto</th>
                        <th>Solicitante</th>
                        <th>Dependencia</th>
                        <th>Tipo</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Asignado a</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td><strong>{{ $ticket->codigo }}</strong></td>
                            <td>{{ Str::limit($ticket->asunto, 30) }}</td>
                            <td>{{ $ticket->solicitante->nombre_completo }}</td>
                            <td>{{ $ticket->dependencia->nombre }}</td>
                            <td><span class="badge badge-info">{{ Ticket::tiposServicio()[$ticket->tipo_servicio] }}</span></td>
                            <td><span class="badge {{ $ticket->prioridad_badge }}">{{ Ticket::prioridades()[$ticket->prioridad] }}</span></td>
                            <td><span class="badge {{ $ticket->estado_badge }}">{{ Ticket::estados()[$ticket->estado] }}</span></td>
                            <td>{{ $ticket->asignadoA->nombre_completo ?? 'Sin asignar' }}</td>
                            <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No hay tickets registrados</td>
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