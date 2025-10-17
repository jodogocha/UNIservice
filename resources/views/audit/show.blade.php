@extends('adminlte::page')

@section('title', 'Detalle de Auditoría')

@section('content_header')
    <h1><i class="fas fa-history"></i> Detalle del Registro de Auditoría</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información General</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">ID:</dt>
                        <dd class="col-sm-9">{{ $log->id }}</dd>

                        <dt class="col-sm-3">Fecha y Hora:</dt>
                        <dd class="col-sm-9">{{ $log->created_at->format('d/m/Y H:i:s') }}</dd>

                        <dt class="col-sm-3">Usuario:</dt>
                        <dd class="col-sm-9">
                            @if($log->user)
                                <a href="{{ route('usuarios.show', $log->user) }}">{{ $log->user_name }}</a>
                            @else
                                {{ $log->user_name }}
                            @endif
                        </dd>

                        <dt class="col-sm-3">Acción:</dt>
                        <dd class="col-sm-9">
                            <span class="badge {{ $log->action_badge }} badge-lg">
                                {{ $log->action_name }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Módulo:</dt>
                        <dd class="col-sm-9"><code>{{ $log->module }}</code></dd>

                        <dt class="col-sm-3">Registro ID:</dt>
                        <dd class="col-sm-9">{{ $log->record_id ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Descripción:</dt>
                        <dd class="col-sm-9">{{ $log->description }}</dd>

                        <dt class="col-sm-3">Dirección IP:</dt>
                        <dd class="col-sm-9"><code>{{ $log->ip_address }}</code></dd>

                        <dt class="col-sm-3">Navegador:</dt>
                        <dd class="col-sm-9"><small>{{ $log->user_agent }}</small></dd>
                    </dl>
                </div>
            </div>

            @if($log->old_values || $log->new_values)
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title"><i class="fas fa-exchange-alt"></i> Cambios Realizados</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($log->old_values)
                                <div class="col-md-6">
                                    <h5>Valores Anteriores</h5>
                                    <pre class="bg-light p-3">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                            
                            @if($log->new_values)
                                <div class="col-md-6">
                                    <h5>Valores Nuevos</h5>
                                    <pre class="bg-light p-3">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Acciones</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('audit.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-clock"></i> Tiempo</h3>
                </div>
                <div class="card-body">
                    <p><strong>Hace:</strong> {{ $log->created_at->diffForHumans() }}</p>
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