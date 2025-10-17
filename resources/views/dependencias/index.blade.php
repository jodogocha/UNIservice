@extends('adminlte::page')

@section('title', 'Dependencias')

@section('content_header')
    <h1><i class="fas fa-building"></i> Gestión de Dependencias</h1>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Filtros --}}
    <div class="card collapsed-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="display: none;">
            <form action="{{ route('dependencias.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="buscar">Buscar:</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="buscar" 
                                   name="buscar" 
                                   value="{{ request('buscar') }}"
                                   placeholder="Nombre de la dependencia">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="unidad_academica">Unidad Académica:</label>
                            <select class="form-control" id="unidad_academica" name="unidad_academica">
                                <option value="">Todas</option>
                                @foreach($unidadesAcademicas as $unidad)
                                    <option value="{{ $unidad->id }}" {{ request('unidad_academica') == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="">Todos</option>
                                <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Activos</option>
                                <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Listado --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Dependencias</h3>
            <div class="card-tools">
                @can('users.create')
                    <a href="{{ route('dependencias.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Nueva Dependencia
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Unidad Académica</th>
                        <th>Usuarios</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dependencias as $dependencia)
                        <tr>
                            <td>{{ $dependencia->id }}</td>
                            <td><code>{{ $dependencia->codigo }}</code></td>
                            <td><strong>{{ $dependencia->nombre }}</strong></td>
                            <td>{{ $dependencia->unidadAcademica->nombre }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $dependencia->users_count }} {{ Str::plural('usuario', $dependencia->users_count) }}
                                </span>
                            </td>
                            <td>
                                @if($dependencia->activo)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @can('users.view')
                                        <a href="{{ route('dependencias.show', $dependencia) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('users.edit')
                                        <a href="{{ route('dependencias.edit', $dependencia) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('dependencias.cambiar-estado', $dependencia) }}" 
                                              method="POST" 
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $dependencia->activo ? 'btn-warning' : 'btn-success' }}" 
                                                    title="{{ $dependencia->activo ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas fa-{{ $dependencia->activo ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    @endcan
                                    
                                    @can('users.delete')
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmarEliminacion({{ $dependencia->id }})" 
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="form-delete-{{ $dependencia->id }}" 
                                              action="{{ route('dependencias.destroy', $dependencia) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay dependencias registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $dependencias->links() }}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
function confirmarEliminacion(id) {
    if (confirm('¿Está seguro de eliminar esta dependencia?\n\nEsta acción no se puede deshacer.')) {
        document.getElementById('form-delete-' + id).submit();
    }
}

setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@stop