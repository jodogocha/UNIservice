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
                <div class="row">
                    <div class="col-md-12">
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
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($unidadSeleccionada)
        <div class="row">
            {{-- Configuración de Módulos --}}
            <div class="col-md-8">
                {{-- Módulos Activos --}}
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-puzzle-piece"></i> Módulos del Sistema para {{ $unidadSeleccionada->nombre }}
                        </h3>
                    </div>
                    <form action="{{ route('configuracion.update-modulos') }}" method="POST">
                        @csrf
                        <input type="hidden" name="unidad_id" value="{{ $unidadSeleccionada->id }}">
                        
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Selecciona los módulos que estarán disponibles para los usuarios de 
                                <strong>{{ $unidadSeleccionada->nombre }}</strong>. Los módulos aparecerán en el menú lateral izquierdo.
                            </div>

                            {{-- TICKETS --}}
                            <div class="mb-4">
                                <h5 class="text-secondary">
                                    <i class="fas fa-tag"></i> TICKETS
                                </h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card {{ in_array('tickets', $unidadSeleccionada->modulos_activos ?? []) ? 'border-warning bg-light' : 'border-secondary' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-switch custom-switch-lg mr-3">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_tickets" 
                                                               name="modulos[]" 
                                                               value="tickets"
                                                               {{ in_array('tickets', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_tickets"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-ticket-alt fa-2x text-warning mr-3"></i>
                                                            <div>
                                                                <strong class="d-block">Gestión de Tickets</strong>
                                                                <small class="text-muted">Sistema de tickets de servicio y soporte técnico</small>
                                                                <span class="badge badge-info ml-2"><i class="fas fa-star"></i> Recomendado</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ADMINISTRACIÓN --}}
                            <div class="mb-4">
                                <h5 class="text-secondary">
                                    <i class="fas fa-tag"></i> ADMINISTRACIÓN
                                </h5>
                                <div class="row">
                                    {{-- Usuarios --}}
                                    <div class="col-md-6">
                                        <div class="card {{ in_array('usuarios', $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary bg-light' : 'border-secondary' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-switch custom-switch-lg mr-2">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_usuarios" 
                                                               name="modulos[]" 
                                                               value="usuarios"
                                                               {{ in_array('usuarios', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_usuarios"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-users fa-2x text-blue mr-2"></i>
                                                            <div>
                                                                <strong class="d-block">Gestión de Usuarios</strong>
                                                                <small class="text-muted">Administración de usuarios y permisos</small>
                                                                <span class="badge badge-info d-block mt-1"><i class="fas fa-star"></i> Recomendado</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Dependencias --}}
                                    <div class="col-md-6">
                                        <div class="card {{ in_array('dependencias', $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary bg-light' : 'border-secondary' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-switch custom-switch-lg mr-2">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_dependencias" 
                                                               name="modulos[]" 
                                                               value="dependencias"
                                                               {{ in_array('dependencias', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_dependencias"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-building fa-2x text-teal mr-2"></i>
                                                            <div>
                                                                <strong class="d-block">Gestión de Dependencias</strong>
                                                                <small class="text-muted">Administración de departamentos y áreas</small>
                                                                <span class="badge badge-info d-block mt-1"><i class="fas fa-star"></i> Recomendado</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Auditoría --}}
                                    <div class="col-md-6">
                                        <div class="card {{ in_array('auditoria', $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary bg-light' : 'border-secondary' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-switch custom-switch-lg mr-2">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_auditoria" 
                                                               name="modulos[]" 
                                                               value="auditoria"
                                                               {{ in_array('auditoria', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_auditoria"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-history fa-2x text-orange mr-2"></i>
                                                            <div>
                                                                <strong class="d-block">Auditoría del Sistema</strong>
                                                                <small class="text-muted">Registro de actividades y cambios</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- REPORTES --}}
                            <div class="mb-4">
                                <h5 class="text-secondary">
                                    <i class="fas fa-tag"></i> REPORTES
                                </h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card {{ in_array('reportes', $unidadSeleccionada->modulos_activos ?? []) ? 'border-danger bg-light' : 'border-secondary' }}">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-switch custom-switch-lg mr-3">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="modulo_reportes" 
                                                               name="modulos[]" 
                                                               value="reportes"
                                                               {{ in_array('reportes', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="modulo_reportes"></label>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-chart-bar fa-2x text-danger mr-3"></i>
                                                            <div>
                                                                <strong class="d-block">Reportes y Estadísticas</strong>
                                                                <small class="text-muted">Generación de reportes y análisis de datos</small>
                                                                <span class="badge badge-info ml-2"><i class="fas fa-star"></i> Recomendado</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- LABORATORIO --}}
                            <div class="mb-4">
                                <h5 class="text-secondary">
                                    <i class="fas fa-tag"></i> LABORATORIO
                                </h5>
                                <div class="row">
                                    {{-- Inventario --}}
                                    <div class="col-md-4">
                                        <div class="card {{ in_array('inventario', $unidadSeleccionada->modulos_activos ?? []) ? 'border-success bg-light' : 'border-secondary' }}">
                                            <div class="card-body">
                                                <div class="custom-control custom-switch custom-switch-lg">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="modulo_inventario" 
                                                           name="modulos[]" 
                                                           value="inventario"
                                                           {{ in_array('inventario', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="modulo_inventario"></label>
                                                </div>
                                                <div class="text-center mt-2">
                                                    <i class="fas fa-laptop fa-3x text-success"></i>
                                                    <strong class="d-block mt-2">Inventario</strong>
                                                    <small class="text-muted">Control de equipos</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Préstamos --}}
                                    <div class="col-md-4">
                                        <div class="card {{ in_array('prestamos', $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary bg-light' : 'border-secondary' }}">
                                            <div class="card-body">
                                                <div class="custom-control custom-switch custom-switch-lg">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="modulo_prestamos" 
                                                           name="modulos[]" 
                                                           value="prestamos"
                                                           {{ in_array('prestamos', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="modulo_prestamos"></label>
                                                </div>
                                                <div class="text-center mt-2">
                                                    <i class="fas fa-handshake fa-3x text-primary"></i>
                                                    <strong class="d-block mt-2">Préstamos</strong>
                                                    <small class="text-muted">Gestión de préstamos</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Uso del Laboratorio --}}
                                    <div class="col-md-4">
                                        <div class="card {{ in_array('usos', $unidadSeleccionada->modulos_activos ?? []) ? 'border-purple bg-light' : 'border-secondary' }}">
                                            <div class="card-body">
                                                <div class="custom-control custom-switch custom-switch-lg">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="modulo_usos" 
                                                           name="modulos[]" 
                                                           value="usos"
                                                           {{ in_array('usos', $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="modulo_usos"></label>
                                                </div>
                                                <div class="text-center mt-2">
                                                    <i class="fas fa-calendar-check fa-3x text-purple"></i>
                                                    <strong class="d-block mt-2">Uso del Laboratorio</strong>
                                                    <small class="text-muted">Reservas y horarios</small>
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
        .custom-switch-lg .custom-control-label::before {
            height: 1.5rem;
            width: 2.75rem;
        }
        .custom-switch-lg .custom-control-label::after {
            width: calc(1.5rem - 4px);
            height: calc(1.5rem - 4px);
        }
        .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after {
            transform: translateX(1.25rem);
        }
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