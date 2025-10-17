@extends('adminlte::page')

@section('title', 'Editar Unidad Académica')

@section('content_header')
    <h1><i class="fas fa-university"></i> Editar Unidad Académica</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información de la Unidad Académica</h3>
        </div>
        <form action="{{ route('unidades-academicas.update', $unidadesAcademica) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $unidadesAcademica->nombre) }}"
                                   required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="codigo">Código <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo" 
                                   name="codigo" 
                                   value="{{ old('codigo', $unidadesAcademica->codigo) }}"
                                   required>
                            @error('codigo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Estado</label>
                            <input type="hidden" name="activo" value="0">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="activo" 
                                       name="activo" 
                                       value="1"
                                       {{ old('activo', $unidadesAcademica->activo) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="activo">
                                    Activo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" 
                              name="descripcion" 
                              rows="3">{{ old('descripcion', $unidadesAcademica->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- SECCIÓN DEL LOGO - AGREGADA --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo">Logo de la Unidad Académica</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" 
                                           class="custom-file-input @error('logo') is-invalid @enderror" 
                                           id="logo" 
                                           name="logo"
                                           accept="image/*">
                                    <label class="custom-file-label" for="logo">Seleccionar nuevo logo...</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB. Dimensiones recomendadas: 150x150px
                            </small>
                            @error('logo')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Logo Actual</label>
                            <div class="text-center p-3 border rounded bg-light">
                                @if($unidadesAcademica->logo)
                                    <img src="{{ asset($unidadesAcademica->logo) }}" 
                                         alt="{{ $unidadesAcademica->nombre }}" 
                                         class="img-thumbnail"
                                         style="max-width: 150px; max-height: 150px;">
                                    <p class="mb-0 mt-2 small text-muted">{{ $unidadesAcademica->logo }}</p>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p>Sin logo asignado</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Preview del nuevo logo --}}
                <div class="row" id="logoPreviewContainer" style="display: none;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Vista previa del nuevo logo</label>
                            <div class="text-center p-3 border rounded bg-light">
                                <img id="logoPreview" src="" alt="Preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('unidades-academicas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
    // Preview del logo cuando se selecciona un archivo
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Mostrar preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
                document.getElementById('logoPreviewContainer').style.display = 'block';
            }
            reader.readAsDataURL(file);
            
            // Actualizar el label con el nombre del archivo
            const fileName = file.name;
            e.target.nextElementSibling.innerText = fileName;
        } else {
            document.getElementById('logoPreviewContainer').style.display = 'none';
        }
    });

    // Inicializar el componente de archivo personalizado de Bootstrap
    bsCustomFileInput.init();
</script>
@stop