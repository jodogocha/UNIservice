@extends('adminlte::page')

@section('title', 'Detalle del Rol')

@section('content_header')
    <h1><i class="fas fa-user-tag"></i> Detalle del Rol</h1>
@stop

@section('content')
    <div class="row">
        {{-- Información Principal --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Rol</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Nombre:</dt>
                        <dd class="col-sm-9"><strong>{{ $role->nombre }}</strong></dd>

                        <dt class="col-sm-3">Identificador:</dt>
                        <dd class="col-sm-9"><code>{{ $role->slug }}</code></dd>

                        <dt class="col-sm-3">Descripción:</dt>
                        <dd class="col-sm-9">{{ $role->descripcion ?? 'Sin descripción' }}</dd>

                        <dt class="col-sm-3">Usuarios Asignados:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-info badge-lg">
                                {{ $role->users->count() }} {{ Str::plural('usuario', $role->users->count()) }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Permisos:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-primary badge-lg">
                                {{ $role->permissions->count() }} {{ Str::plural('permiso', $role->permissions->count()) }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Fecha de Creación:</dt>
                        <dd class="col-sm-9">{{ $role->created_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title"><i class="fas fa-shield-alt"></i> Permisos Asignados</h3>
                </div>
                <div class="card-body">
                    @php
                        $permisosPorCategoria = [
                            'Tickets' => $role->permissions->filter(fn($p) => str_starts_with($p->slug, 'tickets.')),
                            'Usuarios' => $role->permissions->filter(fn($p) => str_starts_with($p->slug, 'users.')),
                            'Reportes' => $role->permissions->filter(fn($p) => str_starts_with($p->slug, 'reports.')),
                            'Auditoría' => $role->permissions->filter(fn($p) => str_starts_with($p->slug, 'audit.')),
                            'Configuración' => $role->permissions->filter(fn($p) => str_starts_with($p->slug, 'config.')),
                        ];
                    @endphp

                    @if($role->permissions->count() > 0)
                        <div class="row">
                            @foreach($permisosPorCategoria as $categoria => $permisos)
                                @if($permisos->count() > 0)
                                    <div class="col-md-6 mb-3">
                                        <h6><i class="fas fa-folder"></i> {{ $categoria }}</h6>
                                        <ul class="list-unstyled ml-3">
                                            @foreach($permisos as $permiso)
                                                <li>
                                                    <i class="fas fa-check-circle text-success"></i> 
                                                    {{ $permiso->nombre }}
                                                    <br>
                                                    <small class="text-muted">{{ $permiso->descripcion }}</small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Este rol no tiene permisos asignados</p>
                    @endif
                </div>
            </div>

            @if($role->users->count() > 0)
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><i class="fas fa-users"></i> Usuarios con este Rol</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Dependencia</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($role->users as $usuario)
                                    <tr>
                                        <td>{{ $usuario->nombre_completo }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>{{ $usuario->dependencia->nombre ?? 'N/A' }}</td>
                                        <td>
                                            @if($usuario->activo)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Panel Lateral --}}
        <div class="col-md-4">
            {{-- Acciones --}}
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Acciones</h3>
                </div>
                <div class="card-body">
                    @can('users.edit')
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Editar Rol
                        </a>
                    @endcan

                    @can('users.delete')
                        @if(!in_array($role->slug, ['admin', 'encargado-lab', 'funcionario']) && $role->users->count() == 0)
                            <button type="button" class="btn btn-danger btn-block mt-2" onclick="confirmarEliminacion()">
                                <i class="fas fa-trash"></i> Eliminar Rol
                            </button>
                            <form id="form-delete" action="{{ route('roles.destroy', $role) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    @endcan

                    <hr>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>

            {{-- Estadísticas --}}
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Estadísticas</h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Usuarios</span>
                            <span class="info-box-number">{{ $role->users->count() }}</span>
                        </div>
                    </div>

                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-shield-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Permisos</span>
                            <span class="info-box-number">{{ $role->permissions->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if(in_array($role->slug, ['admin', 'encargado-lab', 'funcionario']))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Rol del Sistema</strong><br>
                    Este es un rol predefinido del sistema y no puede ser eliminado.
                </div>
            @endif
        </div>
    </div>
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
function confirmarEliminacion() {
    if (confirm('¿Está seguro de eliminar este rol?\n\nEsta acción no se puede deshacer.')) {
        document.getElementById('form-delete').submit();
    }
}
</script>
@stop