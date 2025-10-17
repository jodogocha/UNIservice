@extends('adminlte::page')

@section('title', 'Auditoría del Sistema')

@section('content_header')
    <h1><i class="fas fa-history"></i> Auditoría del Sistema</h1>
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
            <form action="{{ route('audit.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="user_id">Usuario:</label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">Todos</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ request('user_id') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="action">Acción:</label>
                            <select class="form-control" id="action" name="action">
                                <option value="">Todas</option>
                                @foreach($acciones as $accion)
                                    <option value="{{ $accion }}" {{ request('action') == $accion ? 'selected' : '' }}>
                                        {{ ucfirst($accion) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="module">Módulo:</label>
                            <select class="form-control" id="module" name="module">
                                <option value="">Todos</option>
                                @foreach($modulos as $modulo)
                                    <option value="{{ $modulo }}" {{ request('module') == $modulo ? 'selected' : '' }}>
                                        {{ ucfirst($modulo) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_desde">Desde:</label>
                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_hasta">Hasta:</label>
                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="row mb-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $logs->total() }}</h3>
                    <p>Total de Registros</p>
                </div>
                <div class="icon">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ \App\Models\AuditLog::where('action', 'create')->count() }}</h3>
                    <p>Creaciones</p>
                </div>
                <div class="icon">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ \App\Models\AuditLog::where('action', 'update')->count() }}</h3>
                    <p>Actualizaciones</p>
                </div>
                <div class="icon">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ \App\Models\AuditLog::where('action', 'delete')->count() }}</h3>
                    <p>Eliminaciones</p>
                </div>
                <div class="icon">
                    <i class="fas fa-trash"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Listado --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Registros de Auditoría</h3>
            <div class="card-tools">
                @can('audit.manage')
                    <a href="{{ route('audit.export', request()->all()) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel"></i> Exportar CSV
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Módulo</th>
                        <th>Descripción</th>
                        <th>IP</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                            </td>
                            <td>{{ $log->user_name }}</td>
                            <td>
                                <span class="badge {{ $log->action_badge }}">
                                    {{ $log->action_name }}
                                </span>
                            </td>
                            <td><code>{{ $log->module }}</code></td>
                            <td>{{ Str::limit($log->description, 50) }}</td>
                            <td><small>{{ $log->ip_address }}</small></td>
                            <td>
                                <a href="{{ route('audit.show', $log) }}" class="btn btn-xs btn-info" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay registros de auditoría</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $logs->links() }}
        </div>
    </div>

    {{-- Modal para limpiar logs antiguos --}}
    @can('audit.manage')
        <div class="modal fade" id="cleanModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('audit.clean') }}" method="POST">
                        @csrf
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title">Limpiar Logs Antiguos</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Esta acción eliminará permanentemente los registros de auditoría antiguos.
                            </div>
                            <div class="form-group">
                                <label>Eliminar registros más antiguos de:</label>
                                <select class="form-control" name="days" required>
                                    <option value="30">30 días</option>
                                    <option value="60">60 días</option>
                                    <option value="90" selected>90 días</option>
                                    <option value="180">180 días (6 meses)</option>
                                    <option value="365">365 días (1 año)</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-broom"></i> Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@stop