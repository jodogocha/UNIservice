@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('title', 'Crear Ticket')

@section('content_header')
    <h1><i class="fas fa-plus-circle"></i> Crear Nuevo Ticket</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Solicitud de Servicio</h3>
        </div>
        <form action="{{ route('tickets.store') }}" method="POST" id="formTicket">
            @csrf
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Complete el formulario con los detalles de su solicitud de servicio.
                </div>

                <div class="form-group">
                    <label for="asunto">Asunto <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('asunto') is-invalid @enderror" 
                           id="asunto" 
                           name="asunto" 
                           value="{{ old('asunto') }}"
                           placeholder="Breve descripción del problema"
                           required>
                    @error('asunto')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción Detallada <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" 
                              name="descripcion" 
                              rows="5" 
                              placeholder="Describa el problema o solicitud con el mayor detalle posible"
                              required>{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">
                        Incluya toda la información relevante: qué sucede, cuándo ocurre, equipos involucrados, etc.
                    </small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo_servicio">Tipo de Servicio <span class="text-danger">*</span></label>
                            <select class="form-control @error('tipo_servicio') is-invalid @enderror" 
                                    id="tipo_servicio" 
                                    name="tipo_servicio" 
                                    required>
                                <option value="">Seleccione...</option>
                                @foreach($tiposServicio as $key => $value)
                                    <option value="{{ $key }}" {{ old('tipo_servicio') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_servicio')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prioridad">Prioridad <span class="text-danger">*</span></label>
                            <select class="form-control @error('prioridad') is-invalid @enderror" 
                                    id="prioridad" 
                                    name="prioridad" 
                                    required>
                                <option value="">Seleccione...</option>
                                @foreach($prioridades as $key => $value)
                                    <option value="{{ $key }}" {{ old('prioridad', 'media') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prioridad')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Urgente: requiere atención inmediata | Alta: importante | Media: normal | Baja: puede esperar
                            </small>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <strong>Nota:</strong> Una vez creado el ticket, recibirás un código único. Podrás hacer seguimiento y solo tú podrás confirmar cuando el servicio esté finalizado.
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Crear Ticket
                </button>
                <a href="{{ route('tickets.mis-tickets') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Validación en tiempo real
    $('#formTicket').on('submit', function(e) {
        let valid = true;
        
        if ($('#asunto').val().trim() === '') {
            $('#asunto').addClass('is-invalid');
            valid = false;
        } else {
            $('#asunto').removeClass('is-invalid');
        }
        
        if ($('#descripcion').val().trim() === '') {
            $('#descripcion').addClass('is-invalid');
            valid = false;
        } else {
            $('#descripcion').removeClass('is-invalid');
        }
        
        if (!valid) {
            e.preventDefault();
            alert('Por favor complete todos los campos obligatorios');
        }
    });
    
    // Limpiar validación al escribir
    $('.form-control').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@stop