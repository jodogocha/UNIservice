@extends('adminlte::page')

@section('title', 'Detalle del Ticket')

@section('content_header')
    <h1><i class="fas fa-ticket-alt"></i> Detalle del Ticket</h1>
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

    <div class="row">
        {{-- Información del Ticket --}}
        <div class="col-md-8">
            {{-- Datos Principales --}}
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Información del Ticket
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light">{{ $ticket->codigo }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Código:</dt>
                        <dd class="col-sm-9"><strong>{{ $ticket->codigo }}</strong></dd>

                        <dt class="col-sm-3">Asunto:</dt>
                        <dd class="col-sm-9"><strong>{{ $ticket->asunto }}</strong></dd>

                        <dt class="col-sm-3">Descripción:</dt>
                        <dd class="col-sm-9">{{ $ticket->descripcion }}</dd>

                        <dt class="col-sm-3">Tipo de Servicio:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-info">{{ $ticket->tipo_servicio_nombre }}</span>
                        </dd>

                        <dt class="col-sm-3">Prioridad:</dt>
                        <dd class="col-sm-9">
                            <span class="badge {{ $ticket->prioridad_badge }}">
                                {{ $ticket->prioridad_nombre }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Estado:</dt>
                        <dd class="col-sm-9">
                            <span class="badge {{ $ticket->estado_badge }} badge-lg">
                                {{ $ticket->estado_nombre }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Solicitante:</dt>
                        <dd class="col-sm-9">{{ $ticket->solicitante->nombre_completo }}</dd>

                        <dt class="col-sm-3">Dependencia:</dt>
                        <dd class="col-sm-9">{{ $ticket->dependencia->nombre }}</dd>

                        <dt class="col-sm-3">Unidad Académica:</dt>
                        <dd class="col-sm-9">{{ $ticket->unidadAcademica->nombre }}</dd>

                        <dt class="col-sm-3">Asignado a:</dt>
                        <dd class="col-sm-9">
                            @if($ticket->asignado)
                                {{ $ticket->asignado->nombre_completo }}
                            @else
                                <span class="text-muted">Sin asignar</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Fecha de Creación:</dt>
                        <dd class="col-sm-9">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>

                        @if($ticket->fecha_asignacion)
                            <dt class="col-sm-3">Fecha de Asignación:</dt>
                            <dd class="col-sm-9">{{ $ticket->fecha_asignacion->format('d/m/Y H:i') }}</dd>
                        @endif

                        @if($ticket->fecha_listo)
                            <dt class="col-sm-3">Fecha Listo:</dt>
                            <dd class="col-sm-9">{{ $ticket->fecha_listo->format('d/m/Y H:i') }}</dd>
                        @endif

                        @if($ticket->fecha_finalizado)
                            <dt class="col-sm-3">Fecha Finalizado:</dt>
                            <dd class="col-sm-9">{{ $ticket->fecha_finalizado->format('d/m/Y H:i') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Solución (si existe) --}}
            @if($ticket->solucion)
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-check-circle"></i> Solución Aplicada</h3>
                    </div>
                    <div class="card-body">
                        <p>{{ $ticket->solucion }}</p>
                    </div>
                </div>
            @endif

            {{-- Observaciones (si existen) --}}
            @if($ticket->observaciones)
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-comment"></i> Observaciones</h3>
                    </div>
                    <div class="card-body">
                        <pre style="white-space: pre-wrap;">{{ $ticket->observaciones }}</pre>
                    </div>
                </div>
            @endif
        </div>

        {{-- Panel Lateral de Acciones --}}
        <div class="col-md-4">
            {{-- Acciones según permisos --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Acciones</h3>
                </div>
                <div class="card-body">
                    {{-- Volver --}}
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block mb-2">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>

                    {{-- Asignar ticket (solo admin/encargado) --}}
                    @can('tickets.view-all')
                        @if($ticket->estado == 'pendiente' || !$ticket->asignado_a)
                            <button type="button" class="btn btn-primary btn-block mb-2" data-toggle="modal" data-target="#asignarModal">
                                <i class="fas fa-user-plus"></i> Asignar Ticket
                            </button>
                        @endif
                    @endcan

                    {{-- Marcar como listo (solo encargado) --}}
                    @can('tickets.mark-ready')
                        @if($ticket->estado == 'en_proceso')
                            <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#listoModal">
                                <i class="fas fa-check"></i> Marcar como Listo
                            </button>
                        @endif
                    @endcan

                    {{-- Finalizar ticket (solo solicitante) --}}
                    @if($ticket->solicitante_id == auth()->id() && $ticket->estado == 'listo')
                        <form action="{{ route('tickets.finalizar', $ticket) }}" method="POST" onsubmit="return confirm('¿Confirmas que has retirado tu equipo/dispositivo?')">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block mb-2">
                                <i class="fas fa-check-double"></i> Confirmar y Finalizar
                            </button>
                        </form>
                    @endif

                    {{-- Cancelar ticket --}}
                    @if(in_array($ticket->estado, ['pendiente', 'en_proceso']) && ($ticket->solicitante_id == auth()->id() || auth()->user()->hasPermission('tickets.delete')))
                        <button type="button" class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#cancelarModal">
                            <i class="fas fa-times"></i> Cancelar Ticket
                        </button>
                    @endif

                    {{-- Agregar observación --}}
                    @can('tickets.view-all')
                        <button type="button" class="btn btn-warning btn-block mb-2" data-toggle="modal" data-target="#observacionModal">
                            <i class="fas fa-comment"></i> Agregar Observación
                        </button>
                    @endcan

                    {{-- Editar (solo admin) --}}
                    @can('tickets.edit')
                        <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-edit"></i> Editar Ticket
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Información adicional --}}
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clock"></i> Tiempos</h3>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>Creado:</strong> {{ $ticket->created_at->diffForHumans() }}<br>
                        @if($ticket->updated_at != $ticket->created_at)
                            <strong>Actualizado:</strong> {{ $ticket->updated_at->diffForHumans() }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Asignar --}}
    @can('tickets.view-all')
        <div class="modal fade" id="asignarModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('tickets.asignar', $ticket) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title">Asignar Ticket</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Asignar a <span class="text-danger">*</span></label>
                                <select class="form-control" name="asignado_a" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($usuariosParaAsignar as $usuario)
                                        <option value="{{ $usuario->id }}" {{ $ticket->asignado_a == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Asignar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    {{-- Modal para Marcar como Listo --}}
    @can('tickets.mark-ready')
        <div class="modal fade" id="listoModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('tickets.marcar-listo', $ticket) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-success">
                            <h5 class="modal-title">Marcar como Listo</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Solución Aplicada <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="solucion" rows="4" required placeholder="Describe la solución aplicada al problema..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>Observaciones adicionales</label>
                                <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales (opcional)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Marcar como Listo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    {{-- Modal para Cancelar --}}
    <div class="modal fade" id="cancelarModal" tabindex="-1">
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
                            <textarea class="form-control" name="motivo" rows="3" required placeholder="Explica por qué se cancela este ticket"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Cancelar Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal para Agregar Observación --}}
    @can('tickets.view-all')
        <div class="modal fade" id="observacionModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('tickets.observacion', $ticket) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title">Agregar Observación</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Observación <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="observacion" rows="3" required placeholder="Escribe tu observación..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
        }
    </style>
@stop

@section('js')
<script>
// Auto-ocultar alertas después de 5 segundos
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@stop