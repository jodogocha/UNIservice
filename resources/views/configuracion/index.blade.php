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
                    <div class="col-md-10">
                        <select class="form-control form-control-lg" id="unidad_id" name="unidad_id" onchange="this.form.submit()">
                            @foreach($unidadesAcademicas as $unidad)
                                <option value="{{ $unidad->id }}" 
                                        {{ $unidadSeleccionada && $unidadSeleccionada->id == $unidad->id ? 'selected' : '' }}>
                                    {{ $unidad->nombre }} ({{ $unidad->codigo }})
                                    - {{ count($unidad->modulos_activos ?? []) }} módulos activos
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-info btn-lg btn-block" data-toggle="modal" data-target="#modalCopiar">
                            <i class="fas fa-copy"></i> Copiar Config
                        </button>
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
                                <strong>{{ $unidadSeleccionada->nombre }}</strong>.
                            </div>

                            <div class="row">
                                @foreach($modulosDisponibles as $key => $modulo)
                                    <div class="col-md-6 mb-3">
                                        <div class="card {{ in_array($key, $unidadSeleccionada->modulos_activos ?? []) ? 'border-primary bg-light' : '' }}">
                                            <div class="card-body">
                                                <div class="custom-control custom-switch custom-switch-lg">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="modulo_{{ $key }}" 
                                                           name="modulos[]" 
                                                           value="{{ $key }}"
                                                           {{ in_array($key, $unidadSeleccionada->modulos_activos ?? []) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="modulo_{{ $key }}">
                                                        <i class="{{ $modulo['icono'] }} fa-fw fa-2x"></i>
                                                        <strong class="d-block">{{ $modulo['nombre'] }}</strong>
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block mt-2">
                                                    {{ $modulo['descripcion'] }}
                                                </small>
                                                @if($modulo['activo_por_defecto'])
                                                    <span class="badge badge-info mt-2">
                                                        <i class="fas fa-star"></i> Recomendado
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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

    {{-- Modal para copiar configuración --}}
    <div class="modal fade" id="modalCopiar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('configuracion.copiar') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-info">
                        <h5 class="modal-title">
                            <i class="fas fa-copy"></i> Copiar Configuración
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="unidad_origen_id">Copiar desde:</label>
                            <select class="form-control" id="unidad_origen_id" name="unidad_origen_id" required>
                                @foreach($unidadesAcademicas as $unidad)
                                    <option value="{{ $unidad->id }}" 
                                            {{ $unidadSeleccionada && $unidadSeleccionada->id == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="unidad_destino_id">Copiar hacia:</label>
                            <select class="form-control" id="unidad_destino_id" name="unidad_destino_id" required>
                                @foreach($unidadesAcademicas as $unidad)
                                    <option value="{{ $unidad->id }}">
                                        {{ $unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Atención:</strong> Esta acción sobrescribirá toda la configuración de módulos 
                            y configuración avanzada de la unidad de destino.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-copy"></i> Copiar Configuración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .custom-switch-lg .custom-control-label::before {
            height: 2rem;
            width: 3.5rem;
        }
        .custom-switch-lg .custom-control-label::after {
            width: calc(2rem - 4px);
            height: calc(2rem - 4px);
        }
        .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after {
            transform: translateX(1.5rem);
        }
        .badge-lg {
            font-size: 1rem;
            padding: 0.4rem 0.6rem;
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