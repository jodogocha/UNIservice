@extends('adminlte::page')

@section('title', 'Configuración del Sistema')

@section('content_header')
    <h1><i class="fas fa-cogs"></i> Configuración del Sistema</h1>
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

    <div class="row">
        {{-- Configuración General --}}
        <div class="col-md-8">
            {{-- Módulos Activos --}}
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-puzzle-piece"></i> Módulos del Sistema
                    </h3>
                </div>
                <form action="{{ route('configuracion.update-modulos') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <p class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Selecciona los módulos que deseas utilizar en tu unidad académica.
                        </p>

                        <div class="row">
                            @foreach($modulosDisponibles as $key => $modulo)
                                <div class="col-md-6 mb-3">
                                    <div class="card {{ in_array($key, $unidadAcademica->modulos_activos ?? []) ? 'border-primary' : '' }}">
                                        <div class="card-body">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="modulo_{{ $key }}" 
                                                       name="modulos[]" 
                                                       value="{{ $key }}"
                                                       {{ in_array($key, $unidadAcademica->modulos_activos ?? []) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="modulo_{{ $key }}">
                                                    <i class="{{ $modulo['icono'] }} fa-fw"></i>
                                                    <strong>{{ $modulo['nombre'] }}</strong>
                                                </label>
                                            </div>
                                            <small class="text-muted d-block mt-2">
                                                {{ $modulo['descripcion'] }}
                                            </small>
                                            @if($modulo['activo_por_defecto'])
                                                <span class="badge badge-info mt-2">Recomendado</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Módulos
                        </button>
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
                    <div class="card-body">
                        <h5><i class="fas fa-ticket-alt"></i> Tickets</h5>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="tickets_auto_asignar" 
                                       name="tickets_auto_asignar"
                                       {{ ($unidadAcademica->configuracion['tickets']['auto_asignar'] ?? false) ? 'checked' : '' }}>
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
                                       {{ ($unidadAcademica->configuracion['tickets']['require_approval'] ?? false) ? 'checked' : '' }}>
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
                                       {{ ($unidadAcademica->configuracion['notificaciones']['email'] ?? true) ? 'checked' : '' }}>
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
                        <i class="fas fa-university"></i> Información de la Unidad
                    </h3>
                </div>
                <form action="{{ route('configuracion.update-general') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        {{-- Logo Actual --}}
                        <div class="text-center mb-3">
                            <img src="{{ asset($unidadAcademica->logo ?? 'images/logos/default.png') }}" 
                                 alt="Logo" 
                                 class="img-fluid"
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
                                   value="{{ $unidadAcademica->nombre }}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="codigo">Código</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="codigo" 
                                   name="codigo" 
                                   value="{{ $unidadAcademica->codigo }}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="3">{{ $unidadAcademica->descripcion }}</textarea>
                        </div>

                        <dl class="row">
                            <dt class="col-sm-6">Módulos Activos:</dt>
                            <dd class="col-sm-6">
                                <span class="badge badge-primary">
                                    {{ count($unidadAcademica->modulos_activos ?? []) }}
                                </span>
                            </dd>

                            <dt class="col-sm-6">Dependencias:</dt>
                            <dd class="col-sm-6">
                                <span class="badge badge-info">
                                    {{ $unidadAcademica->dependencias->count() }}
                                </span>
                            </dd>

                            <dt class="col-sm-6">Usuarios:</dt>
                            <dd class="col-sm-6">
                                <span class="badge badge-success">
                                    {{ $unidadAcademica->users->count() }}
                                </span>
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
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
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