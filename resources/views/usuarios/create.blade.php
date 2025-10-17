@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <h1><i class="fas fa-user-plus"></i> Crear Nuevo Usuario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información del Usuario</h3>
        </div>
        <form action="{{ route('usuarios.store') }}" method="POST" id="formUsuario">
            @csrf
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
                                   value="{{ old('name') }}"
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
                                   value="{{ old('apellido') }}"
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
                                   value="{{ old('documento') }}"
                                   placeholder="Ej: 1234567">
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
                                   value="{{ old('email') }}"
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
                                   value="{{ old('telefono') }}"
                                   placeholder="Ej: 0981234567">
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
                                    <option value="{{ $unidad->id }}" {{ old('unidad_academica_id') == $unidad->id ? 'selected' : '' }}>
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
                                    required 
                                    disabled>
                                <option value="">Primero seleccione una unidad académica</option>
                            </select>
                            @error('dependencia_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Seguridad --}}
                <h5 class="mb-3"><i class="fas fa-lock"></i> Datos de Acceso</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Mínimo 8 caracteres</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
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
                                               {{ in_array($rol->id, old('roles', [])) ? 'checked' : '' }}>
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
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="activo" 
                                       name="activo" 
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="activo">
                                    Usuario Activo
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Los usuarios inactivos no pueden iniciar sesión
                            </small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Usuario
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
    // Cargar dependencias cuando se selecciona una unidad académica
    $('#unidad_academica_id').change(function() {
        var unidadId = $(this).val();
        var dependenciaSelect = $('#dependencia_id');
        
        if (unidadId) {
            $.ajax({
                url: '/api/dependencias/' + unidadId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    dependenciaSelect.prop('disabled', false);
                    dependenciaSelect.empty();
                    dependenciaSelect.append('<option value="">Seleccione...</option>');
                    
                    $.each(data, function(key, dependencia) {
                        dependenciaSelect.append(
                            '<option value="' + dependencia.id + '">' + 
                            dependencia.nombre + 
                            '</option>'
                        );
                    });

                    // Reseleccionar el valor anterior si existe (para old())
                    var oldDependencia = "{{ old('dependencia_id') }}";
                    if (oldDependencia) {
                        dependenciaSelect.val(oldDependencia);
                    }
                },
                error: function() {
                    alert('Error al cargar las dependencias');
                }
            });
        } else {
            dependenciaSelect.prop('disabled', true);
            dependenciaSelect.empty();
            dependenciaSelect.append('<option value="">Primero seleccione una unidad académica</option>');
        }
    });

    // Si hay un valor old de unidad académica, disparar el evento change
    @if(old('unidad_academica_id'))
        $('#unidad_academica_id').trigger('change');
    @endif

    // Validación de contraseñas
    $('#formUsuario').submit(function(e) {
        var password = $('#password').val();
        var passwordConfirm = $('#password_confirmation').val();
        
        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            return false;
        }
    });
});
</script>
@stop