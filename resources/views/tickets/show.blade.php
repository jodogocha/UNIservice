@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('title', 'Detalle del Ticket')

@section('content_header')
    <h1><i class="fas fa-ticket-alt"></i> Ticket {{ $ticket->codigo }}</h1>
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
        <!-- Información principal -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Ticket</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Código:</dt>
                        <dd class="col-sm-9"><strong>{{ $ticket->codigo }}</strong></dd>

                        <dt class="col-sm-3">Estado:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-lg {{ $ticket->estado_badge }}">
                                {{ Ticket::estados()[$ticket->estado] }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Asunto:</dt>
                        <dd class="col-sm-9">{{ $ticket->asunto }}</dd>

                        <dt class="col-sm-3">Descripción:</dt>
                        <dd class="col-sm-9">{{ $ticket->descripcion }}</dd>

                        <dt class="col-sm-3">Tipo de Servicio:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-info">{{ Ticket::tiposServicio()[$ticket->tipo_servicio] }}</span>
                        </dd>

                        <dt class="col-sm-3">Prioridad:</dt>
                        <dd class="col-sm-9">
                            <span class="badge {{ $ticket->prioridad_badge }}">{{ Ticket::prioridades()[$ticket->prioridad] }}</span>
                        </dd>

                        @if($ticket->solucion)
                            <dt class="col-sm-3">Solución Aplicada:</dt>
                            <dd class="col-sm-9">
                                <div class="alert alert-success">
                                    {{ $ticket->solucion }}
                                </div>
                            </dd>
                        @endif

                        @if($ticket->observaciones)
                            <dt class="col-sm-3">Observaciones:</dt>
                            <dd class="col-sm-9">
                                <div class="alert alert-info">
                                    {!! nl2br(e($ticket->observaciones)) !!}
                                </div>
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Acciones del ticket -->
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Acciones</h3>
                </div>
                <div class="card-body">
                    
                    @can('tickets.view-all')
                        @if($ticket->estado === 'pendiente' || $ticket->estado === 'en_proceso')
                            <!-- Asignar ticket -->
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalAsignar">
                                <i class="fas fa-user-plus"></i> Asignar Ticket
                            </button>

                            <!-- Marcar como listo -->
                            @can('tickets.mark-ready')
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalMarcarListo">
                                    <i class="fas fa-check-circle"></i> Marcar como Listo
                                </button>
                            @endcan
                        @endif
                    @endcan

                    @if($ticket->esSolicitante(auth()->id()) && $ticket->estado === 'listo')
                        <!-- Finalizar ticket (solo solicitante) -->
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalFinalizar">
                            <i class="fas fa-check-double"></i> Confirmar Finalización
                        </button>
                    @endif

                    @if($ticket->estado !== 'finalizado' && $ticket->estado !== 'cancelado')
                        <!-- Agregar observación -->
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalObservacion">
                            <i class="fas fa-comment"></i> Agregar Observación
                        </button>

                        <!-- Cancelar ticket -->
                        @if($ticket->esSolicitante(auth()->id()) || auth()->user()->hasRole('admin'))
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCancelar">
                                <i class="fas fa-times-circle"></i> Cancelar Ticket
                            </button>
                        @endif
                    @endif

                </div>
            </div>
        </div>

        <!-- Información del solicitante y fechas -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-user"></i> Solicitante</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong><br>{{ $ticket->solicitante->nombre_completo }}</p>
                    <p><strong>Email:</strong><br>{{ $ticket->solicitante->email }}</p>
                    <p><strong>Dependencia:</strong><br>{{ $ticket->dependencia->nombre }}</p>
                    <p><strong>Unidad Académica:</strong><br>{{ $ticket->unidadAcademica->nombre }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title"><i class="fas fa-calendar"></i> Fechas</h3>
                </div>
                <div class="card-body">
                    <p><strong>Creación:</strong><br>{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                    
                    @if($ticket->fecha_asignacion)
                        <p><strong>Asignación:</strong><br>{{ $ticket->fecha_asignacion->format('d/m/Y H:i') }}</p>
                    @endif
                    
                    @if($ticket->fecha_listo)
                        <p><strong>Marcado como listo:</strong><br>{{ $ticket->fecha_listo->format('d/m/Y H:i') }}</p>
                    @endif
                    
                    @if($ticket->fecha_finalizado)
                        <p><strong>Finalizado:</strong><br>{{ $ticket->fecha_finalizado->format('d/m/Y H:i') }}</p>
                    @endif

                    @if($ticket->asignadoA)
                        <hr>
                        <p><strong>Asignado a:</strong><br>{{ $ticket->asignadoA->nombre_completo }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modales -->
    
    <!-- Modal Asignar -->
    @can('tickets.view-all')
    <div class="modal fade" id="modalAsignar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('tickets.asignar', $ticket) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title">Asignar Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="asignado_a">Asignar a:</label>
                            <select class="form-control" id="asignado_a" name="asignado_a" required>
                                <option value="">Seleccione un usuario...</option>
                                @foreach($usuariosParaAsignar as $usuario)
                                    <option value="{{ $usuario->id }}" {{ $ticket->asignado_a == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->nombre_completo }} ({{ $usuario->roles->first()->nombre ?? 'Sin rol' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info">Asignar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endcan

    <!-- Modal Marcar como Listo -->
    @can('tickets.mark-ready')
    <div class="modal fade" id="modalMarcarListo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('tickets.marcar-listo', $ticket) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title">Marcar como Listo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="solucion">Solución Aplicada: <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="solucion" name="solucion" rows="4" required 
                                      placeholder="Describa la solución que se aplicó al problema"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="observaciones">Observaciones adicionales:</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                                      placeholder="Información adicional (opcional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Marcar como Listo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endcan

    <!-- Modal Finalizar -->
    @if($ticket->esSolicitante(auth()->id()))
    <div class="modal fade" id="modalFinalizar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('tickets.finalizar', $ticket) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title">Confirmar Finalización</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Confirma que el servicio ha sido completado satisfactoriamente?</p>
                        <div class="alert alert-warning">
                            <strong>Nota:</strong> Una vez finalizado, el ticket se cerrará y no podrá ser modificado.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Sí, Finalizar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Modal Agregar Observación -->
    <div class="modal fade" id="modalObservacion" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('tickets.observacion', $ticket) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Agregar Observación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="observacion">Observación:</label>
                            <textarea class="form-control" id="observacion" name="observacion" rows="4" required 
                                      placeholder="Escriba su observación o comentario"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Cancelar -->
    @if($ticket->esSolicitante(auth()->id()) || auth()->user()->hasRole('admin'))
    <div class="modal fade" id="modalCancelar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('tickets.cancelar', $ticket) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title">Cancelar Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="motivo">Motivo de cancelación: <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="motivo" name="motivo" rows="3" required 
                                      placeholder="Indique por qué desea cancelar este ticket"></textarea>
                        </div>
                        <div class="alert alert-danger">
                            <strong>Advertencia:</strong> Esta acción no se puede deshacer.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No, volver</button>
                        <button type="submit" class="btn btn-danger">Sí, Cancelar Ticket</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
@stop