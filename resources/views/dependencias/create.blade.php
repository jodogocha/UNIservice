@extends('adminlte::page')

@section('title', 'Crear Dependencia')

@section('content_header')
    <h1><i class="fas fa-building"></i> Crear Nueva Dependencia</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información de la Dependencia</h3>
        </div>
        <form action="{{ route('dependencias.store') }}" method="POST">
            @csrf
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}"
                                   placeholder="Ej: Laboratorio de Informática"
                                   required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="codigo">Código <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo" 
                                   name="codigo" 
                                   value="{{ old('codigo') }}"
                                   placeholder="Ej: LAB-INFO"
                                   required>
                            @error('codigo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Código único de identificación</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Estado</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="activo" 
                                       name="activo" 
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="activo">
                                    Activo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="unidad_academica_id">Unidad Académica <span class="text-danger">*</span></label>
                    <select class="form-control @error('unidad_academica_id') is-invalid @enderror" 
                            id="unidad_academica_id" 
                            name="unidad_academica_id" 
                            required>
                        <option value="">Seleccione...</option>
                        @foreach($unidadesAcademicas as $unidad)
                            <option value="{{ $unidad->id }}" {{ old('unidad_academica_id') == $unidad->id ? 'selected' : '' }}>
                                {{ $unidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('unidad_academica_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" 
                              name="descripcion" 
                              rows="3"
                              placeholder="Descripción breve de la dependencia">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('dependencias.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop