@extends('adminlte::page')

@section('title', 'Todos los Tickets')

@section('content_header')
    <h1><i class="fas fa-list-alt"></i> Todos los Tickets</h1>
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
            <form action="{{ route('tickets.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="buscar">Buscar:</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="buscar" 
                                   name="buscar" 
                                   value="{{ request('buscar') }}"
                                   placeholder="Código o asunto del ticket">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="">Todos</option>
                                @foreach(\App\Models\Ticket::estados() as $key => $value)
                                    <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="prioridad">Prioridad:</label>
                            <select class="form-control" id="prioridad" name="prioridad">
                                <option value="">Todas</option>
                                @foreach(\App\Models\Ticket::prioridades() as $key => $value)
                                    <option value="{{ $key }}" {{ request('prioridad') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_servicio">Tipo:</label>
                            <select class="form-control" id="tipo_servicio" name="tipo_servicio">
                                <option value="">Todos</option>
                                @foreach(\App\Models\Ticket::tiposServicio() as $key => $value)
                                    <option value="{{ $key }}" {{ request('tipo_servicio') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
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

    {{-- Listado de tickets --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Tickets</h3>
            <div class="card-tools">
                @can('tickets.create')
                    <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Nuevo Ticket
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Asunto</th>
                        <th>Solicitante</th>
                        <th>Dependencia</th>
                        <th>Tipo</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Asignado a</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>
                                <strong>{{ $ticket->codigo }}</strong>
                            </td>
                            <td>{{ Str::limit($ticket->asunto, 30) }}</td>
                            <td>{{ $ticket->solicitante->nombre_completo }}</td>
                            <td>{{ $ticket->dependencia->nombre }}</td>
                            <td>{{ $ticket->tipo_servicio_nombre }}</td>
                            <td>
                                <span class="badge {{ $ticket->prioridad_badge }}">
                                    {{ $ticket->prioridad_nombre }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $ticket->estado_badge }}">
                                    {{ $ticket->estado_nombre }}
                                </span>
                            </td>
                            <td>
                                {{ $ticket->asignado->nombre_completo ?? 'Sin asignar' }}
                            </td>
                            <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('tickets.show', $ticket) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @can('tickets.edit')
                                        <a href="{{ route('tickets.edit', $ticket) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('tickets.mark-ready')
                                        @if($ticket->estado == 'en_proceso')
                                            <form action="{{ route('tickets.marcar-listo', $ticket) }}" 
                                                  method="POST" 
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success" 
                                                        title="Marcar como listo">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No hay tickets registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $tickets->links() }}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
// Auto-ocultar alertas después de 5 segundos
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@stop