@extends('adminlte::page')

@section('title', 'Detalle de Dependencia')

@section('content_header')
    <h1><i class="fas fa-building"></i> Detalle de Dependencia</h1>
@stop

@section('content')
    <div class="row">
        {{-- Información Principal --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información General</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nombre:</dt>
                        <dd class="col-sm-8"><strong>{{ $dependencia->nombre }}</strong></dd>

                        <dt class="col-sm-4">Código:</dt>
                        <dd class="col-sm-8"><code>{{ $dependencia->codigo }}</code></dd>

                        <dt class="col-sm-4">Unidad Académica:</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('unidades-academicas.show', $dependencia->unidadAcademica) }}">
                                {{ $dependencia->unidadAcademica->nombre }}
                            </a>
                        </dd>

                        <dt class="col-sm-4">Descripción:</dt>
                        <dd class="col-sm-8">{{ $dependencia->descripcion ?? 'Sin descripción' }}</dd>

                        <dt class="col-sm-4">Estado:</dt>
                        <dd class="col-sm-8">
                            @if($dependencia->activo)
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            @else
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Usuarios:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-info badge-lg">
                                {{ $dependencia->users->count() }} {{ Str::plural('usuario', $dependencia->users->count()) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Fecha de Creación:</dt>
                        <dd class="col-sm-8">{{ $dependencia->created_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            @if($dependencia->users->count() > 0)
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><i class="fas fa-users"></i> Usuarios de esta Dependencia</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dependencia->users as $usuario)
                                    <tr>
                                        <td>{{ $usuario->nombre_completo }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>
                                            @foreach($usuario->roles as $rol)
                                                <span class="badge badge-info">{{ $rol->nombre }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($usuario->activo)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
                        <a href="{{ route('dependencias.edit', $dependencia) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Editar
                        </a>

                        <form action="{{ route('dependencias.cambiar-estado', $dependencia) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-{{ $dependencia->activo ? 'warning' : 'success' }} btn-block">
                                <i class="fas fa-{{ $dependencia->activo ? 'ban' : 'check' }}"></i> 
                                {{ $dependencia->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    @endcan

                    @can('users.delete')
                        @if($dependencia->users->count() == 0)
                            <hr>
                            <button type="button" class="btn btn-danger btn-block" onclick="confirmarEliminacion()">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                            <form id="form-delete" action="{{ route('dependencias.destroy', $dependencia) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    @endcan

                    <hr>
                    <a href="{{ route('dependencias.index') }}" class="btn btn-secondary btn-block">
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
                            <span class="info-box-text">Usuarios Totales</span>
                            <span class="info-box-number">{{ $dependencia->users->count() }}</span>
                        </div>
                    </div>

                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Usuarios Activos</span>
                            <span class="info-box-number">{{ $dependencia->users->where('activo', true)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
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
    if (confirm('¿Está seguro de eliminar esta dependencia?\n\nEsta acción no se puede deshacer.')) {
        document.getElementById('form-delete').submit();
    }
}
</script>
@stop