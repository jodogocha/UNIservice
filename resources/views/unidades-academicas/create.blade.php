@extends('adminlte::page')

@section('title', 'Crear Unidad Académica')

@section('content_header')
    <h1><i class="fas fa-university"></i> Crear Nueva Unidad Académica</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Datos de la Unidad Académica</h3>
        </div>
        
        <form action="{{ route('unidades-academicas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}" 
                                   required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo">Código <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo" 
                                   name="codigo" 
                                   value="{{ old('codigo') }}" 
                                   required>
                            @error('codigo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" 
                              name="descripcion" 
                              rows="3">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="logo">Logo de la Unidad Académica</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input @error('logo') is-invalid @enderror" 
                                   id="logo" 
                                   name="logo"
                                   accept="image/*">
                            <label class="custom-file-label" for="logo">Seleccionar archivo...</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB. Dimensiones recomendadas: 150x150px
                    </small>
                    @error('logo')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    
                    <!-- Preview del logo -->
                    <div id="logoPreview" class="mt-2" style="display: none;">
                        <img id="logoPreviewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="activo" 
                               name="activo" 
                               value="1" 
                               {{ old('activo', true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="activo">Activo</label>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('unidades-academicas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
<script>
    // Preview del logo
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreviewImg').src = e.target.result;
                document.getElementById('logoPreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
            
            // Actualizar el label
            const fileName = file.name;
            e.target.nextElementSibling.innerText = fileName;
        }
    });
</script>
@stop