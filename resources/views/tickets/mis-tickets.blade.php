@extends('adminlte::page')

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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Estadísticas rápidas --}}
    <div class="row mb-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $tickets->total() }}</h3>
                    <p>Total de Tickets</p>
                </div>
                <div class="icon">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $tickets->where('estado', 'pendiente')->count() }}</h3>
                    <p>Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $tickets->where('estado', 'en_proceso')->count() }}</h3>
                    <p>En Proceso</p>
                </div>
                <div class="icon">
                    <i class="fas fa-sync"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $tickets->where('estado', 'listo')->count() }}</h3>
                    <p>Listos para Retiro</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Listado de tickets --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Mis Tickets</h3>
            <div class="card-tools">
                @can('tickets.create')
                    <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Nuevo Ticket
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Asunto</th>
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
                            <td>
                                <strong>{{ $ticket->codigo }}</strong>
                            </td>
                            <td>{{ Str::limit($ticket->asunto, 40) }}</td>
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
                            <td>
                                {{ $ticket->asignado->nombre_completo ?? 'Sin asignar' }}
                            </td>
                            <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('tickets.show', $ticket) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($ticket->estado == 'listo')
                                        <form action="{{ route('tickets.finalizar', $ticket) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Confirmas que has retirado tu equipo/dispositivo?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-success" 
                                                    title="Confirmar y Finalizar">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($ticket->estado, ['pendiente', 'en_proceso']))
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                data-toggle="modal" 
                                                data-target="#cancelarModal{{ $ticket->id }}"
                                                title="Cancelar ticket">
                                            <i class="fas fa-times"></i>
                                        </button>

                                        {{-- Modal para cancelar --}}
                                        <div class="modal fade" id="cancelarModal{{ $ticket->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('tickets.cancelar', $ticket) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-danger">
                                                            <h5 class="modal-title">Cancelar Ticket</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Motivo de cancelación <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" 
                                                                          name="motivo" 
                                                                          rows="3" 
                                                                          required
                                                                          placeholder="Explica por qué deseas cancelar este ticket"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                Cerrar
                                                            </button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-times"></i> Cancelar Ticket
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No tienes tickets registrados</p>
                                    @can('tickets.create')
                                        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Crear mi primer ticket
                                        </a>
                                    @endcan
                                </div>
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

    {{-- Información adicional --}}
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Información sobre los Estados</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">
                    <span class="badge badge-warning">Pendiente</span>
                </dt>
                <dd class="col-sm-9">Tu ticket ha sido registrado y está esperando ser asignado a un técnico.</dd>

                <dt class="col-sm-3">
                    <span class="badge badge-info">En Proceso</span>
                </dt>
                <dd class="col-sm-9">Un técnico está trabajando en tu solicitud.</dd>

                <dt class="col-sm-3">
                    <span class="badge badge-primary">Listo para Retiro</span>
                </dt>
                <dd class="col-sm-9">El trabajo ha sido completado. Puedes pasar a retirar tu equipo y confirmar.</dd>

                <dt class="col-sm-3">
                    <span class="badge badge-success">Finalizado</span>
                </dt>
                <dd class="col-sm-9">El ticket ha sido cerrado correctamente.</dd>

                <dt class="col-sm-3">
                    <span class="badge badge-danger">Cancelado</span>
                </dt>
                <dd class="col-sm-9">El ticket fue cancelado por el solicitante o administrador.</dd>
            </dl>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
// Auto-ocultar alertas después de 5 segundos
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@stop