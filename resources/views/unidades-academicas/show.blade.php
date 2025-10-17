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
                                    <th>Usuarios</th>
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
                                            <span class="badge badge-info">
                                                {{ $dependencia->users_count ?? 0 }} {{ Str::plural('usuario', $dependencia->users_count ?? 0) }}
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
                                            <a href="{{ route('dependencias.show', $dependencia) }}" 
                                               class="btn btn-sm btn-info"
                                               title="Ver detalle">
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

        {{-- Panel Lateral con Logo --}}
        <div class="col-md-4">
            {{-- Logo de la Unidad Académica --}}
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-image"></i> Logo</h3>
                </div>
                <div class="card-body text-center">
                    @if($unidadesAcademica->logo)
                        <img src="{{ asset($unidadesAcademica->logo) }}" 
                             alt="Logo {{ $unidadesAcademica->nombre }}" 
                             class="img-fluid img-thumbnail"
                             style="max-width: 200px;">
                        <p class="mt-3 mb-0 small text-muted">
                            <i class="fas fa-info-circle"></i> {{ $unidadesAcademica->logo }}
                        </p>
                    @else
                        <div class="text-muted py-5">
                            <i class="fas fa-image fa-5x mb-3"></i>
                            <p>No hay logo asignado</p>
                            <a href="{{ route('unidades-academicas.edit', $unidadesAcademica) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-upload"></i> Subir Logo
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Acciones --}}
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Acciones</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('users.edit')
                            <a href="{{ route('unidades-academicas.edit', $unidadesAcademica) }}" 
                               class="btn btn-warning btn-block mb-2">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        @endcan

                        @can('users.edit')
                            <form action="{{ route('unidades-academicas.cambiar-estado', $unidadesAcademica) }}" 
                                  method="POST" 
                                  class="mb-2">
                                @csrf
                                @if($unidadesAcademica->activo)
                                    <button type="submit" class="btn btn-secondary btn-block">
                                        <i class="fas fa-ban"></i> Desactivar
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check"></i> Activar
                                    </button>
                                @endif
                            </form>
                        @endcan

                        <a href="{{ route('unidades-academicas.index') }}" 
                           class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>

                        @can('users.delete')
                            @if($unidadesAcademica->dependencias->count() == 0)
                                <form action="{{ route('unidades-academicas.destroy', $unidadesAcademica) }}" 
                                      method="POST"
                                      onsubmit="return confirm('¿Está seguro de eliminar esta unidad académica?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop