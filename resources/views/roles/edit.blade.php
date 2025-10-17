@extends('adminlte::page')

@section('title', 'Editar Rol')

@section('content_header')
    <h1><i class="fas fa-user-tag"></i> Editar Rol</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información del Rol</h3>
        </div>
        <form action="{{ route('roles.update', $role) }}" method="POST" id="formRol">
            @csrf
            @method('PUT')
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre del Rol <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $role->nombre) }}"
                                   required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="slug">Identificador (Slug) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug', $role->slug) }}"
                                   {{ in_array($role->slug, ['admin', 'encargado-lab', 'funcionario']) ? 'readonly' : '' }}
                                   required>
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            @if(in_array($role->slug, ['admin', 'encargado-lab', 'funcionario']))
                                <small class="form-text text-warning">
                                    <i class="fas fa-lock"></i> El slug de roles del sistema no se puede modificar
                                </small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" 
                              name="descripcion" 
                              rows="3">{{ old('descripcion', $role->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <hr>

                <h5 class="mb-3"><i class="fas fa-shield-alt"></i> Permisos del Rol</h5>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Seleccione los permisos que tendrá este rol
                </div>

                <div class="row">
                    @php
                        $permisosRol = $role->permissions->pluck('id')->toArray();
                        $permisosPorCategoria = [
                            'Tickets' => $permissions->filter(fn($p) => str_starts_with($p->slug, 'tickets.')),
                            'Usuarios' => $permissions->filter(fn($p) => str_starts_with($p->slug, 'users.')),
                            'Reportes' => $permissions->filter(fn($p) => str_starts_with($p->slug, 'reports.')),
                            'Auditoría' => $permissions->filter(fn($p) => str_starts_with($p->slug, 'audit.')),
                            'Configuración' => $permissions->filter(fn($p) => str_starts_with($p->slug, 'config.')),
                        ];
                    @endphp

                    @foreach($permisosPorCategoria as $categoria => $permisos)
                        @if($permisos->count() > 0)
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-folder-open"></i> {{ $categoria }}
                                            <button type="button" 
                                                    class="btn btn-xs btn-outline-primary float-right" 
                                                    onclick="toggleCategoria('{{ $categoria }}')">
                                                Seleccionar todos
                                            </button>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach($permisos as $permiso)
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input permiso-{{ $categoria }}" 
                                                       type="checkbox" 
                                                       id="permiso_{{ $permiso->id }}" 
                                                       name="permissions[]" 
                                                       value="{{ $permiso->id }}"
                                                       {{ in_array($permiso->id, old('permissions', $permisosRol)) ? 'checked' : '' }}>
                                                <label for="permiso_{{ $permiso->id }}" class="custom-control-label">
                                                    <strong>{{ $permiso->nombre }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $permiso->descripcion }}</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Rol
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
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
    // Función para seleccionar/deseleccionar todos los permisos de una categoría
    window.toggleCategoria = function(categoria) {
        var checkboxes = $('.permiso-' + categoria);
        var allChecked = checkboxes.filter(':checked').length === checkboxes.length;
        checkboxes.prop('checked', !allChecked);
    };
});
</script>
@stop