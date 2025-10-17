@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1><i class="fas fa-user-edit"></i> Editar Usuario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información del Usuario</h3>
        </div>
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST" id="formUsuario">
            @csrf
            @method('PUT')
            <div class="card-body">
                
                {{-- Datos Personales --}}
                <h5 class="mb-3"><i class="fas fa-user"></i> Datos Personales</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $usuario->name) }}"
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apellido">Apellido <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('apellido') is-invalid @enderror" 
                                   id="apellido" 
                                   name="apellido" 
                                   value="{{ old('apellido', $usuario->apellido) }}"
                                   required>
                            @error('apellido')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="documento">Documento de Identidad</label>
                            <input type="text" 
                                   class="form-control @error('documento') is-invalid @enderror" 
                                   id="documento" 
                                   name="documento" 
                                   value="{{ old('documento', $usuario->documento) }}">
                            @error('documento')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $usuario->email) }}"
                                   required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" 
                                   class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="{{ old('telefono', $usuario->telefono) }}">
                            @error('telefono')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Datos Institucionales --}}
                <h5 class="mb-3"><i class="fas fa-building"></i> Datos Institucionales</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unidad_academica_id">Unidad Académica <span class="text-danger">*</span></label>
                            <select class="form-control @error('unidad_academica_id') is-invalid @enderror" 
                                    id="unidad_academica_id" 
                                    name="unidad_academica_id" 
                                    required>
                                <option value="">Seleccione...</option>
                                @foreach($unidadesAcademicas as $unidad)
                                    <option value="{{ $unidad->id }}" 
                                            {{ old('unidad_academica_id', $usuario->unidad_academica_id) == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unidad_academica_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dependencia_id">Dependencia <span class="text-danger">*</span></label>
                            <select class="form-control @error('dependencia_id') is-invalid @enderror" 
                                    id="dependencia_id" 
                                    name="dependencia_id" 
                                    required>
                                <option value="">Cargando...</option>
                            </select>
                            @error('dependencia_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Cambiar Contraseña --}}
                <h5 class="mb-3"><i class="fas fa-lock"></i> Cambiar Contraseña</h5>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Deja estos campos vacíos si no deseas cambiar la contraseña
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Nueva Contraseña</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Mínimo 8 caracteres</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation">
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Roles y Estado --}}
                <h5 class="mb-3"><i class="fas fa-user-tag"></i> Roles y Permisos</h5>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Roles <span class="text-danger">*</span></label>
                            <div class="@error('roles') is-invalid @enderror">
                                @foreach($roles as $rol)
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" 
                                               type="checkbox" 
                                               id="rol_{{ $rol->id }}" 
                                               name="roles[]" 
                                               value="{{ $rol->id }}"
                                               {{ in_array($rol->id, old('roles', $usuario->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label for="rol_{{ $rol->id }}" class="custom-control-label">
                                            <strong>{{ $rol->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $rol->descripcion }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estado</label>
                            <input type="hidden" name="activo" value="0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                    class="custom-control-input" 
                                    id="activo" 
                                    name="activo" 
                                    value="1"
                                    {{ old('activo', $usuario->activo) ? 'checked' : '' }}
                                    {{ $usuario->id === auth()->id() ? 'disabled' : '' }}>
                                <label class="custom-control-label" for="activo">
                                    Usuario Activo
                                </label>
                            </div>
                            @if($usuario->id === auth()->id())
                                <small class="form-text text-danger">
                                    No puedes desactivar tu propio usuario
                                </small>
                            @else
                                <small class="form-text text-muted">
                                    Los usuarios inactivos no pueden iniciar sesión
                                </small>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Usuario
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
$(document).ready(function() {
    var usuarioDependenciaId = "{{ old('dependencia_id', $usuario->dependencia_id) }}";
    
    // Función para cargar dependencias
    function cargarDependencias(unidadId, dependenciaSeleccionada = null) {
        var dependenciaSelect = $('#dependencia_id');
        
        if (unidadId) {
            $.ajax({
                url: '/api/dependencias/' + unidadId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    dependenciaSelect.empty();
                    dependenciaSelect.append('<option value="">Seleccione...</option>');
                    
                    $.each(data, function(key, dependencia) {
                        var selected = dependenciaSeleccionada == dependencia.id ? 'selected' : '';
                        dependenciaSelect.append(
                            '<option value="' + dependencia.id + '" ' + selected + '>' + 
                            dependencia.nombre + 
                            '</option>'
                        );
                    });
                },
                error: function() {
                    alert('Error al cargar las dependencias');
                }
            });
        }
    }

    // Cargar dependencias al inicio
    var unidadInicial = $('#unidad_academica_id').val();
    if (unidadInicial) {
        cargarDependencias(unidadInicial, usuarioDependenciaId);
    }

    // Cargar dependencias cuando cambia la unidad académica
    $('#unidad_academica_id').change(function() {
        var unidadId = $(this).val();
        cargarDependencias(unidadId);
    });

    // Validación de contraseñas
    $('#formUsuario').submit(function(e) {
        var password = $('#password').val();
        var passwordConfirm = $('#password_confirmation').val();
        
        if (password || passwordConfirm) {
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
        }
    });
});
</script>
@stop