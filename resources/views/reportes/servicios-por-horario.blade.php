@extends('adminlte::page')

@section('title', 'Servicios por Horario')

@section('content_header')
    <h1><i class="fas fa-clock"></i> Servicios por Franja Horaria</h1>
@stop

@section('content')
    {{-- Filtros --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reportes.servicios-horario') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha Inicio:</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                   value="{{ $fechaInicio }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha Fin:</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                   value="{{ $fechaFin }}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora_inicio">Hora Inicio:</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                                   value="{{ $horaInicio }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora_fin">Hora Fin:</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" 
                                   value="{{ $horaFin }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Estadísticas Resumidas --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['total'] }}</h3>
                    <p>Total de Servicios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-list"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['finalizados'] }}</h3>
                    <p>Finalizados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['en_proceso'] }}</h3>
                    <p>En Proceso</p>
                </div>
                <div class="icon">
                    <i class="fas fa-spinner"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $estadisticas['pendientes'] }}</h3>
                    <p>Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> Distribución por Hora
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartServiciosPorHora" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i> Estados
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartEstados"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Tickets --}}
    <div class="card">
        <div class="card-header bg-secondary">
            <h3 class="card-title"><i class="fas fa-table"></i> Detalle de Servicios</h3>
            <div class="card-tools">
                @can('reports.export')
                    <a href="{{ route('reportes.exportar-pdf', 'servicios-horario') }}?fecha_inicio={{ $fechaInicio }}&fecha_fin={{ $fechaFin }}&hora_inicio={{ $horaInicio }}&hora_fin={{ $horaFin }}" 
                       class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha/Hora</th>
                        <th>Solicitante</th>
                        <th>Dependencia</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" target="_blank">
                                    {{ $ticket->codigo }}
                                </a>
                            </td>
                            <td>
                                <small>
                                    <i class="fas fa-calendar"></i> {{ $ticket->created_at->format('d/m/Y') }}<br>
                                    <i class="fas fa-clock"></i> {{ $ticket->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td>{{ $ticket->solicitante->nombre_completo }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $ticket->dependencia->codigo }}
                                </span>
                            </td>
                            <td>{{ $ticket->tipo_servicio_nombre }}</td>
                            <td>
                                <span class="badge {{ $ticket->estado_badge }}">
                                    {{ $ticket->estado_nombre }}
                                </span>
                            </td>
                            <td>{{ $ticket->asignado->nombre_completo ?? 'Sin asignar' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No hay servicios en la franja horaria seleccionada</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Gráfico de línea - Distribución por hora
    const horas = @json(array_keys($ticketsPorHora->toArray()));
    const cantidades = @json(array_values($ticketsPorHora->toArray()));

    const ctxLinea = document.getElementById('chartServiciosPorHora').getContext('2d');
    new Chart(ctxLinea, {
        type: 'line',
        data: {
            labels: horas,
            datasets: [{
                label: 'Servicios',
                data: cantidades,
                borderColor: 'rgba(40, 167, 69, 1)',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgba(40, 167, 69, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Servicios por Hora del Día',
                    font: { size: 14 }
                }
            }
        }
    });

    // Gráfico de pastel - Estados
    const ctxPastel = document.getElementById('chartEstados').getContext('2d');
    new Chart(ctxPastel, {
        type: 'doughnut',
        data: {
            labels: ['Finalizados', 'En Proceso', 'Pendientes'],
            datasets: [{
                data: [
                    {{ $estadisticas['finalizados'] }},
                    {{ $estadisticas['en_proceso'] }},
                    {{ $estadisticas['pendientes'] }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15 }
                }
            }
        }
    });
</script>
@stop