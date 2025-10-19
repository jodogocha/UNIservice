@extends('adminlte::page')

@section('title', 'Ranking de Dependencias')

@section('content_header')
    <h1><i class="fas fa-trophy"></i> Ranking de Dependencias</h1>
@stop

@section('content')
    {{-- Filtros --}}
    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Fecha</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reportes.ranking-dependencias') }}" method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha Inicio:</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                   value="{{ $fechaInicio }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha Fin:</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                   value="{{ $fechaFin }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Top 3 Podio --}}
    <div class="row">
        @if($ranking->count() >= 3)
            {{-- Segundo Lugar --}}
            <div class="col-md-4">
                <div class="card card-outline card-secondary" style="margin-top: 40px;">
                    <div class="card-body text-center">
                        <div class="text-secondary">
                            <i class="fas fa-medal fa-4x"></i>
                        </div>
                        <h3 class="text-secondary">#2</h3>
                        <h4>{{ $ranking[1]->dependencia->nombre }}</h4>
                        <p class="text-muted">{{ $ranking[1]->dependencia->codigo }}</p>
                        <h2 class="text-bold">{{ $ranking[1]->total_solicitudes }}</h2>
                        <p class="text-muted">solicitudes</p>
                    </div>
                </div>
            </div>

            {{-- Primer Lugar --}}
            <div class="col-md-4">
                <div class="card card-outline card-warning">
                    <div class="card-body text-center">
                        <div class="text-warning">
                            <i class="fas fa-trophy fa-5x"></i>
                        </div>
                        <h3 class="text-warning">#1</h3>
                        <h3>{{ $ranking[0]->dependencia->nombre }}</h3>
                        <p class="text-muted">{{ $ranking[0]->dependencia->codigo }}</p>
                        <h1 class="text-bold text-warning">{{ $ranking[0]->total_solicitudes }}</h1>
                        <p class="text-muted">solicitudes</p>
                    </div>
                </div>
            </div>

            {{-- Tercer Lugar --}}
            <div class="col-md-4">
                <div class="card card-outline card-danger" style="margin-top: 60px;">
                    <div class="card-body text-center">
                        <div class="text-danger">
                            <i class="fas fa-medal fa-3x"></i>
                        </div>
                        <h3 class="text-danger">#3</h3>
                        <h5>{{ $ranking[2]->dependencia->nombre }}</h5>
                        <p class="text-muted">{{ $ranking[2]->dependencia->codigo }}</p>
                        <h2 class="text-bold">{{ $ranking[2]->total_solicitudes }}</h2>
                        <p class="text-muted">solicitudes</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Gráfico de Pastel --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Distribución por Dependencia</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartRankingDependencias"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Comparativa</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartBarrasDependencias"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla Completa --}}
    <div class="card">
        <div class="card-header bg-success">
            <h3 class="card-title"><i class="fas fa-list"></i> Ranking Completo</h3>
            <div class="card-tools">
                @can('reports.export')
                    <a href="{{ route('reportes.exportar-pdf', 'ranking-dependencias') }}?fecha_inicio={{ $fechaInicio }}&fecha_fin={{ $fechaFin }}" 
                       class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80px">Posición</th>
                        <th>Dependencia</th>
                        <th>Código</th>
                        <th class="text-center">Total Solicitudes</th>
                        <th width="200px">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalGeneral = $ranking->sum('total_solicitudes');
                    @endphp
                    @foreach($ranking as $index => $item)
                        <tr>
                            <td class="text-center">
                                @if($index == 0)
                                    <span class="badge badge-warning badge-lg">
                                        <i class="fas fa-trophy"></i> {{ $index + 1 }}
                                    </span>
                                @elseif($index == 1)
                                    <span class="badge badge-secondary badge-lg">
                                        <i class="fas fa-medal"></i> {{ $index + 1 }}
                                    </span>
                                @elseif($index == 2)
                                    <span class="badge badge-danger badge-lg">
                                        <i class="fas fa-medal"></i> {{ $index + 1 }}
                                    </span>
                                @else
                                    <span class="badge badge-light badge-lg">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td><strong>{{ $item->dependencia->nombre }}</strong></td>
                            <td><code>{{ $item->dependencia->codigo }}</code></td>
                            <td class="text-center">
                                <span class="badge badge-info badge-lg">{{ $item->total_solicitudes }}</span>
                            </td>
                            <td>
                                @php
                                    $porcentaje = $totalGeneral > 0 ? round(($item->total_solicitudes / $totalGeneral) * 100, 1) : 0;
                                @endphp
                                <div class="progress">
                                    <div class="progress-bar 
                                        @if($index == 0) bg-warning 
                                        @elseif($index == 1) bg-secondary 
                                        @elseif($index == 2) bg-danger 
                                        @else bg-primary @endif" 
                                        role="progressbar" 
                                        style="width: {{ $porcentaje }}%"
                                        aria-valuenow="{{ $porcentaje }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                        {{ $porcentaje }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .badge-lg {
            font-size: 1.1rem;
            padding: 0.5rem 0.75rem;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Datos
    const dependencias = @json($ranking->pluck('dependencia.nombre'));
    const solicitudes = @json($ranking->pluck('total_solicitudes'));

    // Colores
    const colores = [
        'rgba(255, 193, 7, 0.8)',   // Oro
        'rgba(108, 117, 125, 0.8)',  // Plata
        'rgba(220, 53, 69, 0.8)',    // Bronce
        'rgba(0, 123, 255, 0.8)',
        'rgba(40, 167, 69, 0.8)',
        'rgba(23, 162, 184, 0.8)',
        'rgba(111, 66, 193, 0.8)',
        'rgba(253, 126, 20, 0.8)',
        'rgba(255, 7, 58, 0.8)',
        'rgba(13, 202, 240, 0.8)'
    ];

    // Gráfico de Pastel
    const ctxPie = document.getElementById('chartRankingDependencias').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: dependencias,
            datasets: [{
                data: solicitudes,
                backgroundColor: colores,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 15,
                        padding: 10
                    }
                },
                title: {
                    display: true,
                    text: 'Distribución de Solicitudes',
                    font: { size: 14 }
                }
            }
        }
    });

    // Gráfico de Barras Horizontales
    const ctxBar = document.getElementById('chartBarrasDependencias').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: dependencias,
            datasets: [{
                label: 'Solicitudes',
                data: solicitudes,
                backgroundColor: colores,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Top 10 Dependencias',
                    font: { size: 14 }
                }
            }
        }
    });
</script>
@stop