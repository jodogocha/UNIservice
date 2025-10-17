@extends('adminlte::page')

@section('title', 'Detalle de Unidad Académica')

@section('content_header')
    <h1><i class="fas fa-university"></i> Detalle de Unidad Académica</h1>
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
                        <dt class="col-sm-3">Nombre:</dt>
                        <dd class="col-sm-9"><strong>{{ $unidadesAcademica->nombre }}</strong></dd>

                        <dt class="col-sm-3">Código:</dt>
                        <dd class="col-sm-9"><code>{{ $unidadesAcademica->codigo }}</code></dd>

                        <dt class="col-sm-3">Descripción:</dt>
                        <dd class="col-sm-9">{{ $unidadesAcademica->descripcion ?? 'Sin descripción' }}</dd>

                        <dt class="col-sm-3">Estado:</dt>
                        <dd class="col-sm-9">
                            @if($unidadesAcademica->activo)
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            @else
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Dependencias:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-info badge-lg">
                                {{ $unidadesAcademica->dependencias->count() }} {{ Str::plural('dependencia', $unidadesAcademica->dependencias->count()) }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Fecha de Creación:</dt>
                        <dd class="col-sm-9">{{ $unidadesAcademica->created_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            @if($unidadesAcademica->dependencias->count() > 0)
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><i class="fas fa-building"></i> Dependencias</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unidadesAcademica->dependencias as $dependencia)
                                    <tr>
                                        <td><code>{{ $dependencia->codigo }}</code></td>
                                        <td>{{ $dependencia->nombre }}</td>
                                        <td>
                                            @if($dependencia->activo)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('dependencias.show', $dependencia) }}" class="btn btn-sm btn-info">
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
                        <a href="{{ route('unidades-academicas.edit', $unidadesAcademica) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Editar
                        </a>

                        <form action="{{ route('unidades-academicas.cambiar-estado', $unidadesAcademica) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-{{ $unidadesAcademica->activo ? 'warning' : 'success' }} btn-block">
                                <i class="fas fa-{{ $unidadesAcademica->activo ? 'ban' : 'check' }}"></i> 
                                {{ $unidadesAcademica->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    @endcan

                    @can('users.delete')
                        @if($unidadesAcademica->dependencias->count() == 0)
                            <hr>
                            <button type="button" class="btn btn-danger btn-block" onclick="confirmarEliminacion()">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                            <form id="form-delete" action="{{ route('unidades-academicas.destroy', $unidadesAcademica) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    @endcan

                    <hr>
                    <a href="{{ route('unidades-academicas.index') }}" class="btn btn-secondary btn-block">
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
                        <span class="info-box-icon"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Dependencias</span>
                            <span class="info-box-number">{{ $unidadesAcademica->dependencias->count() }}</span>
                        </div>
                    </div>

                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Activas</span>
                            <span class="info-box-number">{{ $unidadesAcademica->dependencias->where('activo', true)->count() }}</span>
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
    if (confirm('¿Está seguro de eliminar esta unidad académica?\n\nEsta acción no se puede deshacer.')) {
        document.getElementById('form-delete').submit();
    }
}
</script>
@stop