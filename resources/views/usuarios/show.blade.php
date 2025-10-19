@extends('adminlte::page')

@section('title', 'Detalle del Usuario')

@section('content_header')
    <h1><i class="fas fa-user"></i> Detalle del Usuario</h1>
@stop

@section('content')
    <div class="row">
        {{-- Información Principal --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información Personal</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nombre Completo:</dt>
                        <dd class="col-sm-8">{{ $usuario->nombre_completo }}</dd>

                        <dt class="col-sm-4">Documento:</dt>
                        <dd class="col-sm-8">{{ $usuario->documento ?? 'No registrado' }}</dd>

                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8">
                            <a href="mailto:{{ $usuario->email }}">{{ $usuario->email }}</a>
                        </dd>

                        <dt class="col-sm-4">Teléfono:</dt>
                        <dd class="col-sm-8">{{ $usuario->telefono ?? 'No registrado' }}</dd>

                        <dt class="col-sm-4">Estado:</dt>
                        <dd class="col-sm-8">
                            @if($usuario->activo)
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle"></i> Activo
                                </span>
                            @else
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-times-circle"></i> Inactivo
                                </span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Fecha de Registro:</dt>
                        <dd class="col-sm-8">{{ $usuario->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Última Actualización:</dt>
                        <dd class="col-sm-8">{{ $usuario->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-building"></i> Información Institucional</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Unidad Académica:</dt>
                        <dd class="col-sm-8">{{ $usuario->unidadAcademica->nombre ?? 'No asignada' }}</dd>

                        <dt class="col-sm-4">Dependencia:</dt>
                        <dd class="col-sm-8">{{ $usuario->dependencia->nombre ?? 'No asignada' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title"><i class="fas fa-user-tag"></i> Roles y Permisos</h3>
                </div>
                <div class="card-body">
                    <h5>Roles Asignados:</h5>
                    <div class="mb-3">
                        @forelse($usuario->roles as $rol)
                            <span class="badge badge-info badge-lg mr-2">
                                <i class="fas fa-shield-alt"></i> {{ $rol->nombre }}
                            </span>
                        @empty
                            <span class="text-muted">Sin roles asignados</span>
                        @endforelse
                    </div>

                    <hr>

                    <h5>Permisos:</h5>
                    <div class="row">
                        @php
                            $allPermissions = [];
                            foreach($usuario->roles as $rol) {
                                foreach($rol->permissions as $permission) {
                                    $allPermissions[$permission->slug] = $permission->nombre;
                                }
                            }
                        @endphp

                        @if(count($allPermissions) > 0)
                            @foreach($allPermissions as $slug => $nombre)
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" checked disabled id="perm_{{ $loop->index }}">
                                        <label class="custom-control-label" for="perm_{{ $loop->index }}">
                                            {{ $nombre }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <span class="text-muted">Sin permisos asignados</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit"></i> Editar Usuario
                        </a>

                        @if($usuario->id !== auth()->id())
                            <form action="{{ route('usuarios.cambiar-estado', $usuario) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-{{ $usuario->activo ? 'warning' : 'success' }} btn-block">
                                    <i class="fas fa-{{ $usuario->activo ? 'ban' : 'check' }}"></i> 
                                    {{ $usuario->activo ? 'Desactivar' : 'Activar' }} Usuario
                                </button>
                            </form>
                        @endif
                    @endcan

                    @can('users.delete')
                        @if($usuario->id !== auth()->id())
                            <hr>
                            <button type="button" class="btn btn-danger btn-block" onclick="confirmarEliminacion()">
                                <i class="fas fa-trash"></i> Eliminar Usuario
                            </button>
                            <form id="form-delete" action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    @endcan

                    <hr>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>

            {{-- Estadísticas --}}
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Estadísticas</h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-ticket-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tickets Creados</span>
                            <span class="info-box-number">{{ $ticketsCreados = $usuario->ticketsCreados->count() }}</span>
                        </div>
                    </div>

                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-tasks"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tickets Asignados</span>
                            <span class="info-box-number">{{ $ticketsAsignados = $usuario->ticketsAsignados->count() }}</span>
                        </div>
                    </div>

                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check-double"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tickets Finalizados</span>
                            <span class="info-box-number">{{ $usuario->tickets()->where('estado', 'finalizado')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Últimos Tickets --}}
            @if($usuario->tickets()->count() > 0)
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title"><i class="fas fa-history"></i> Últimos Tickets</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($usuario->tickets()->latest()->limit(5)->get() as $ticket)
                                <li class="list-group-item">
                                    <strong>{{ $ticket->codigo }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($ticket->asunto, 40) }}</small><br>
                                    <span class="badge {{ $ticket->estado_badge }}">
                                        {{ \App\Models\Ticket::estados()[$ticket->estado] }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('tickets.index') }}?solicitante={{ $usuario->id }}" class="btn btn-sm btn-link">
                            Ver todos los tickets
                        </a>
                    </div>
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
    if (confirm('¿Está seguro de eliminar este usuario?\n\nEsta acción no se puede deshacer y eliminará todos los datos relacionados.')) {
        document.getElementById('form-delete').submit();
    }
}
</script>
@stop