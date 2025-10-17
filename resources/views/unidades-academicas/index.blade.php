@extends('adminlte::page')

@section('title', 'Unidades Académicas')

@section('content_header')
    <h1><i class="fas fa-university"></i> Gestión de Unidades Académicas</h1>
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
            <form action="{{ route('unidades-academicas.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="buscar">Buscar:</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="buscar" 
                                   name="buscar" 
                                   value="{{ request('buscar') }}"
                                   placeholder="Nombre de la unidad académica">
                        </div>
                    </div>
                    <div class="col-md-4">
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
            <h3 class="card-title">Listado de Unidades Académicas</h3>
            <div class="card-tools">
                @can('users.create')
                    <a href="{{ route('unidades-academicas.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Nueva Unidad Académica
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
                        <th>Dependencias</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unidades as $unidad)
                        <tr>
                            <td>{{ $unidad->id }}</td>
                            <td><code>{{ $unidad->codigo }}</code></td>
                            <td><strong>{{ $unidad->nombre }}</strong></td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $unidad->dependencias_count }} {{ Str::plural('dependencia', $unidad->dependencias_count) }}
                                </span>
                            </td>
                            <td>
                                @if($unidad->activo)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @can('users.view')
                                        <a href="{{ route('unidades-academicas.show', $unidad) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('users.edit')
                                        <a href="{{ route('unidades-academicas.edit', $unidad) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('unidades-academicas.cambiar-estado', $unidad) }}" 
                                              method="POST" 
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $unidad->activo ? 'btn-warning' : 'btn-success' }}" 
                                                    title="{{ $unidad->activo ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas fa-{{ $unidad->activo ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    @endcan
                                    
                                    @can('users.delete')
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmarEliminacion({{ $unidad->id }})" 
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="form-delete-{{ $unidad->id }}" 
                                              action="{{ route('unidades-academicas.destroy', $unidad) }}" 
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
                            <td colspan="6" class="text-center">No hay unidades académicas registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $unidades->links() }}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
function confirmarEliminacion(id) {
    if (confirm('¿Está seguro de eliminar esta unidad académica?\n\nEsta acción no se puede deshacer.')) {
        document.getElementById('form-delete-' + id).submit();
    }
}

setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@stop