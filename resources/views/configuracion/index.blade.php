@extends('adminlte::page')

@section('title', 'Configuración del Sistema')

@section('content_header')
    <h1><i class="fas fa-cogs"></i> Configuración del Sistema</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Selector de Unidad Académica --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-university"></i> Seleccionar Unidad Académica
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('configuracion.index') }}" method="GET">
                <select class="form-control form-control-lg" id="unidad_id" name="unidad_id" onchange="this.form.submit()">
                    <option value="">-- Seleccione una Unidad Académica --</option>
                    @foreach($unidadesAcademicas as $unidad)
                        <option value="{{ $unidad->id }}" 
                                {{ $unidadSeleccionada && $unidadSeleccionada->id == $unidad->id ? 'selected' : '' }}>
                            {{ $unidad->nombre }} ({{ $unidad->codigo }})
                            - {{ count($unidad->modulos_activos ?? []) }} módulos activos
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if($unidadSeleccionada)
        <div class="row">
            {{-- Configuración de Módulos --}}
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-puzzle-piece"></i> Módulos del Sistema - {{ $unidadSeleccionada->nombre }}
                        </h3>
                    </div>
                    <form action="{{ route('configuracion.update-modulos') }}" method="POST">
                        @csrf
                        <input type="hidden" name="unidad_id" value="{{ $unidadSeleccionada->id }}">
                        
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Selecciona los módulos que estarán disponibles en el <strong>menú lateral izquierdo</strong> para 
                                los usuarios de <strong>{{ $unidadSeleccionada->nombre }}</strong>.
                            </div>

                            {{-- ADMINISTRACIÓN --}}
                            <div class="mb-4">
                                <h5 class="text-uppercase text-muted mb-3">
                                    <i class="fas fa-chevron-right"></i> ADMINISTRACIÓN
                                </h5>
                                <div class="row">
                                    {{-- Usuarios --}}
                                    <div class="col-md-6 mb-3">
                                        <div class="card {{ in_array('usuarios', $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary shadow-sm' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="custom-control custom-switch mr-3">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_usuarios" 
                                                               name="modulos[]" 
                                                               value="usuarios"
                                                               {{ in_array('usuarios', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_usuarios"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <i class="fas fa-users fa-2x text-blue mb-2"></i>
                                                        <h6 class="mb-1"><strong>Gestión de Usuarios</strong></h6>
                                                        <small class="text-muted d-block">Administración de usuarios y permisos</small>
                                                        @if($modulosDisponibles['usuarios']['activo_por_defecto'])
                                                            <span class="badge badge-info mt-2"><i class="fas fa-star"></i> Recomendado</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Dependencias --}}
                                    <div class="col-md-6 mb-3">
                                        <div class="card {{ in_array('dependencias', $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary shadow-sm' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="custom-control custom-switch mr-3">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_dependencias" 
                                                               name="modulos[]" 
                                                               value="dependencias"
                                                               {{ in_array('dependencias', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_dependencias"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <i class="fas fa-building fa-2x text-teal mb-2"></i>
                                                        <h6 class="mb-1"><strong>Gestión de Dependencias</strong></h6>
                                                        <small class="text-muted d-block">Unidades Académicas y Dependencias</small>
                                                        @if($modulosDisponibles['dependencias']['activo_por_defecto'])
                                                            <span class="badge badge-info mt-2"><i class="fas fa-star"></i> Recomendado</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Auditoría --}}
                                    <div class="col-md-6 mb-3">
                                        <div class="card {{ in_array('auditoria', $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary shadow-sm' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="custom-control custom-switch mr-3">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_auditoria" 
                                                               name="modulos[]" 
                                                               value="auditoria"
                                                               {{ in_array('auditoria', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_auditoria"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <i class="fas fa-history fa-2x text-orange mb-2"></i>
                                                        <h6 class="mb-1"><strong>Auditoría</strong></h6>
                                                        <small class="text-muted d-block">Registro de actividades del sistema</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Reportes --}}
                                    <div class="col-md-6 mb-3">
                                        <div class="card {{ in_array('reportes', $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary shadow-sm' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="custom-control custom-switch mr-3">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_reportes" 
                                                               name="modulos[]" 
                                                               value="reportes"
                                                               {{ in_array('reportes', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_reportes"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <i class="fas fa-chart-bar fa-2x text-danger mb-2"></i>
                                                        <h6 class="mb-1"><strong>Reportes</strong></h6>
                                                        <small class="text-muted d-block">Reportes y estadísticas</small>
                                                        @if($modulosDisponibles['reportes']['activo_por_defecto'])
                                                            <span class="badge badge-info mt-2"><i class="fas fa-star"></i> Recomendado</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- LABORATORIO --}}
                            <div class="mb-4">
                                <h5 class="text-uppercase text-muted mb-3">
                                    <i class="fas fa-chevron-right"></i> LABORATORIO
                                </h5>
                                <div class="row">
                                    {{-- Gestión de Tickets --}}
                                    <div class="col-md-12 mb-3">
                                        <div class="card {{ in_array('tickets', $unidadSeleccionada->modulos_activos ?? []) ? 'border-warning shadow-sm' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="custom-control custom-switch mr-3">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_tickets" 
                                                               name="modulos[]" 
                                                               value="tickets"
                                                               {{ in_array('tickets', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_tickets"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <i class="fas fa-ticket-alt fa-3x text-warning mb-2"></i>
                                                        <h5 class="mb-1"><strong>Gestión de Tickets</strong></h5>
                                                        <small class="text-muted d-block">Sistema de tickets de servicio y soporte técnico</small>
                                                        @if($modulosDisponibles['tickets']['activo_por_defecto'])
                                                            <span class="badge badge-info mt-2"><i class="fas fa-star"></i> Recomendado</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Inventario --}}
                                    <div class="col-md-4 mb-3">
                                        <div class="card {{ in_array('inventario', $unidadSeleccionada->modulos_activos ?? []) ? 'border-success shadow-sm' : '' }}">
                                            <div class="card-body text-center">
                                                <div class="custom-control custom-switch d-inline-block mb-2">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="modulo_inventario" 
                                                           name="modulos[]" 
                                                           value="inventario"
                                                           {{ in_array('inventario', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="modulo_inventario"></label>
                                                </div>
                                                <div>
                                                    <i class="fas fa-laptop fa-3x text-success mb-2"></i>
                                                    <h6 class="mb-1"><strong>Inventario</strong></h6>
                                                    <small class="text-muted d-block">Control de equipos</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Préstamos --}}
                                    <div class="col-md-4 mb-3">
                                        <div class="card {{ in_array('prestamos', $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary shadow-sm' : '' }}">
                                            <div class="card-body text-center">
                                                <div class="custom-control custom-switch d-inline-block mb-2">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="modulo_prestamos" 
                                                           name="modulos[]" 
                                                           value="prestamos"
                                                           {{ in_array('prestamos', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="modulo_prestamos"></label>
                                                </div>
                                                <div>
                                                    <i class="fas fa-handshake fa-3x text-primary mb-2"></i>
                                                    <h6 class="mb-1"><strong>Préstamos</strong></h6>
                                                    <small class="text-muted d-block">Gestión de préstamos</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Uso del Laboratorio --}}
                                    <div class="col-md-4 mb-3">
                                        <div class="card {{ in_array('usos', $unidadSeleccionada->modulos_activos ?? []) ? 'border-purple shadow-sm' : '' }}">
                                            <div class="card-body text-center">
                                                <div class="custom-control custom-switch d-inline-block mb-2">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="modulo_usos" 
                                                           name="modulos[]" 
                                                           value="usos"
                                                           {{ in_array('usos', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="modulo_usos"></label>
                                                </div>
                                                <div>
                                                    <i class="fas fa-calendar-check fa-3x text-purple mb-2"></i>
                                                    <h6 class="mb-1"><strong>Uso del Laboratorio</strong></h6>
                                                    <small class="text-muted d-block">Reservas y horarios</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Guardar Módulos
                            </button>
                            <span class="text-muted ml-3">
                                <i class="fas fa-check-circle"></i>
                                {{ count($unidadSeleccionada->modulos_activos ?? []) }} de {{ count($modulosDisponibles) }} módulos activos
                            </span>
                        </div>
                    </form>
                </div>

                {{-- Configuración Avanzada --}}
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-sliders-h"></i> Configuración Avanzada
                        </h3>
                    </div>
                    <form action="{{ route('configuracion.update-configuracion') }}" method="POST">
                        @csrf
                        <input type="hidden" name="unidad_id" value="{{ $unidadSeleccionada->id }}">
                        
                        <div class="card-body">
                            <h5><i class="fas fa-ticket-alt"></i> Tickets</h5>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="tickets_auto_asignar" 
                                           name="tickets_auto_asignar"
                                           {{ ($unidadSeleccionada->configuracion['tickets']['auto_asignar'] ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="tickets_auto_asignar">
                                        Auto-asignar tickets a técnicos disponibles
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="tickets_require_approval" 
                                           name="tickets_require_approval"
                                           {{ ($unidadSeleccionada->configuracion['tickets']['require_approval'] ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="tickets_require_approval">
                                        Requerir aprobación para cerrar tickets
                                    </label>
                                </div>
                            </div>

                            <hr>

                            <h5><i class="fas fa-bell"></i> Notificaciones</h5>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="notificaciones_email" 
                                           name="notificaciones_email"
                                           {{ ($unidadSeleccionada->configuracion['notificaciones']['email'] ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="notificaciones_email">
                                        Enviar notificaciones por correo electrónico
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-save"></i> Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Información de la Unidad --}}
            <div class="col-md-4">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> Información de la Unidad
                        </h3>
                    </div>
                    <form action="{{ route('configuracion.update-general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="unidad_id" value="{{ $unidadSeleccionada->id }}">
                        
                        <div class="card-body">
                            {{-- Logo Actual --}}
                            <div class="text-center mb-3">
                                <img src="{{ asset($unidadSeleccionada->logo ?? 'images/logos/default.png') }}" 
                                     alt="Logo" 
                                     class="img-fluid img-thumbnail"
                                     style="max-width: 150px;">
                            </div>

                            <div class="form-group">
                                <label for="logo">Cambiar Logo</label>
                                <div class="custom-file">
                                    <input type="file" 
                                           class="custom-file-input" 
                                           id="logo" 
                                           name="logo"
                                           accept="image/*">
                                    <label class="custom-file-label" for="logo">Seleccionar imagen...</label>
                                </div>
                                <small class="form-text text-muted">
                                    Formatos: JPG, PNG, GIF (máx. 2MB)
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ $unidadSeleccionada->nombre }}"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="codigo" 
                                       name="codigo" 
                                       value="{{ $unidadSeleccionada->codigo }}"
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" 
                                          id="descripcion" 
                                          name="descripcion" 
                                          rows="3">{{ $unidadSeleccionada->descripcion }}</textarea>
                            </div>

                            <hr>

                            <dl class="row">
                                <dt class="col-sm-6">Módulos Activos:</dt>
                                <dd class="col-sm-6">
                                    <span class="badge badge-primary badge-lg">
                                        {{ count($unidadSeleccionada->modulos_activos ?? []) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-6">Dependencias:</dt>
                                <dd class="col-sm-6">
                                    <span class="badge badge-info badge-lg">
                                        {{ $unidadSeleccionada->dependencias->count() }}
                                    </span>
                                </dd>

                                <dt class="col-sm-6">Usuarios:</dt>
                                <dd class="col-sm-6">
                                    <span class="badge badge-success badge-lg">
                                        {{ $unidadSeleccionada->users->count() }}
                                    </span>
                                </dd>

                                <dt class="col-sm-6">Estado:</dt>
                                <dd class="col-sm-6">
                                    @if($unidadSeleccionada->activo)
                                        <span class="badge badge-success badge-lg">
                                            <i class="fas fa-check-circle"></i> Activa
                                        </span>
                                    @else
                                        <span class="badge badge-danger badge-lg">
                                            <i class="fas fa-times-circle"></i> Inactiva
                                        </span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-save"></i> Actualizar Información
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.4rem 0.6rem;
        }
        .border-purple {
            border-color: #6f42c1 !important;
        }
        .text-purple {
            color: #6f42c1 !important;
        }
        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script>
$(document).ready(function() {
    bsCustomFileInput.init();
    
    // Auto-ocultar alertas
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@stop