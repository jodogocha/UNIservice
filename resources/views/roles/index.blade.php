@extends('adminlte::page')

@section('title', 'Gestión de Roles')

@section('content_header')
    <h1><i class="fas fa-user-tag"></i> Gestión de Roles</h1>
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Roles</h3>
            <div class="card-tools">
                @can('users.create')
                    <a href="{{ route('roles.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Nuevo Rol
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Identificador (Slug)</th>
                        <th>Descripción</th>
                        <th>Usuarios</th>
                        <th>Permisos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $rol)
                        <tr>
                            <td>{{ $rol->id }}</td>
                            <td><strong>{{ $rol->nombre }}</strong></td>
                            <td><code>{{ $rol->slug }}</code></td>
                            <td>{{ Str::limit($rol->descripcion ?? 'Sin descripción', 50) }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $rol->users_count }} {{ Str::plural('usuario', $rol->users_count) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-primary">
                                    {{ $rol->permissions_count }} {{ Str::plural('permiso', $rol->permissions_count) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    @can('users.view')
                                        <a href="{{ route('roles.show', $rol) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('users.edit')
                                        <a href="{{ route('roles.edit', $rol) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('users.delete')
                                        @if(!in_array($rol->slug, ['admin', 'encargado-lab', 'funcionario']))
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="confirmarEliminacion({{ $rol->id }})" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="form-delete-{{ $rol->id }}" 
                                                  action="{{ route('roles.destroy', $rol) }}" 
                                                  method="POST" 
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-sm btn-secondary" 
                                                    disabled
                                                    title="Rol del sistema (no se puede eliminar)">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay roles registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $roles->links() }}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
function confirmarEliminacion(id) {
    if (confirm('¿Está seguro de eliminar este rol?\n\nEsta acción no se puede deshacer.')) {
        document.getElementById('form-delete-' + id).submit();
    }
}

// Auto-ocultar alertas después de 5 segundos
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@stop