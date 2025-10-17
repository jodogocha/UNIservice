@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <h1><i class="fas fa-users"></i> Gestión de Usuarios</h1>
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
            <form action="{{ route('usuarios.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="buscar">Buscar:</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="buscar" 
                                   name="buscar" 
                                   value="{{ request('buscar') }}"
                                   placeholder="Nombre, apellido, email o documento">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="rol">Rol:</label>
                            <select class="form-control" id="rol" name="rol">
                                <option value="">Todos</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('rol') == $role->id ? 'selected' : '' }}>
                                        {{ $role->nombre }}
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

    {{-- Listado de usuarios --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Usuarios</h3>
            <div class="card-tools">
                @can('users.create')
                    <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Nuevo Usuario
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Dependencia</th>
                        <th>Roles</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->nombre_completo }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->dependencia->nombre ?? 'N/A' }}</td>
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
                                <div class="btn-group">
                                    @can('users.view')
                                        <a href="{{ route('usuarios.show', $usuario) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('users.edit')
                                        <a href="{{ route('usuarios.edit', $usuario) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($usuario->id !== auth()->id())
                                            <form action="{{ route('usuarios.cambiar-estado', $usuario) }}" 
                                                  method="POST" 
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm {{ $usuario->activo ? 'btn-warning' : 'btn-success' }}" 
                                                        title="{{ $usuario->activo ? 'Desactivar' : 'Activar' }}">
                                                    <i class="fas fa-{{ $usuario->activo ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                    
                                    @can('users.delete')
                                        @if($usuario->id !== auth()->id())
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="confirmarEliminacion({{ $usuario->id }})" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="form-delete-{{ $usuario->id }}" 
                                                  action="{{ route('usuarios.destroy', $usuario) }}" 
                                                  method="POST" 
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay usuarios registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $usuarios->links() }}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
function confirmarEliminacion(id) {
    if (confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
        document.getElementById('form-delete-' + id).submit();
    }
}

// Auto-ocultar alertas después de 5 segundos
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@stop