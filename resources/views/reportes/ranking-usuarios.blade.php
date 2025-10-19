@extends('adminlte::page')

@section('title', 'Ranking de Usuarios')

@section('content_header')
    <h1><i class="fas fa-medal"></i> Ranking de Usuarios Solicitantes</h1>
@stop

@section('content')
    {{-- Filtros --}}
    <div class="card">
        <div class="card-header bg-danger">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Fecha</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reportes.ranking-usuarios') }}" method="GET">
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
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Top 10 Usuarios</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartRankingUsuarios" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Estadísticas</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">Total Usuarios:</dt>
                        <dd class="col-sm-6">
                            <span class="badge badge-info badge-lg">{{ $ranking->count() }}</span>
                        </dd>

                        <dt class="col-sm-6">Total Solicitudes:</dt>
                        <dd class="col-sm-6">
                            <span class="badge badge-primary badge-lg">{{ $ranking->sum('total_solicitudes') }}</span>
                        </dd>

                        <dt class="col-sm-6">Promedio:</dt>
                        <dd class="col-sm-6">
                            <span class="badge badge-warning badge-lg">
                                {{ $ranking->count() > 0 ? round($ranking->sum('total_solicitudes') / $ranking->count(), 1) : 0 }}
                            </span>
                        </dd>

                        <dt class="col-sm-6">Más Activo:</dt>
                        <dd class="col-sm-6">
                            @if($ranking->isNotEmpty())
                                <strong>{{ $ranking->first()->solicitante->nombre_completo }}</strong>
                            @endif
                        </dd>
                    </dl>

                    <hr>

                    <div class="text-center">
                        <h5>Período Analizado</h5>
                        <p class="text-muted">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Ranking --}}
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title"><i class="fas fa-list-ol"></i> Ranking Detallado</h3>
            <div class="card-tools">
                @can('reports.export')
                    <a href="{{ route('reportes.exportar-pdf', 'ranking-usuarios') }}?fecha_inicio={{ $fechaInicio }}&fecha_fin={{ $fechaFin }}" 
                       class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="bg-light">
                    <tr>
                        <th width="80px" class="text-center">Pos.</th>
                        <th>Usuario</th>
                        <th>Dependencia</th>
                        <th class="text-center">Total Solicitudes</th>
                        <th width="250px">Actividad</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalGeneral = $ranking->sum('total_solicitudes'); @endphp
                    @forelse($ranking as $index => $item)
                        <tr>
                            <td class="text-center">
                                @if($index == 0)
                                    <span class="badge badge-warning badge-lg" style="font-size: 1.2rem;">
                                        <i class="fas fa-crown"></i> 1°
                                    </span>
                                @elseif($index == 1)
                                    <span class="badge badge-secondary badge-lg" style="font-size: 1.1rem;">
                                        <i class="fas fa-medal"></i> 2°
                                    </span>
                                @elseif($index == 2)
                                    <span class="badge badge-danger badge-lg" style="font-size: 1.1rem;">
                                        <i class="fas fa-medal"></i> 3°
                                    </span>
                                @else
                                    <span class="badge badge-light badge-lg">{{ $index + 1 }}°</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->solicitante->nombre_completo }}</strong>
                                <br>
                                <small class="text-muted">{{ $item->solicitante->email }}</small>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $item->solicitante->dependencia->nombre ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <h4>
                                    <span class="badge badge-primary">{{ $item->total_solicitudes }}</span>
                                </h4>
                            </td>
                            <td>
                                @php
                                    $porcentaje = $totalGeneral > 0 ? round(($item->total_solicitudes / $totalGeneral) * 100, 1) : 0;
                                    $colorClase = $porcentaje > 15 ? 'bg-danger' : ($porcentaje > 10 ? 'bg-warning' : 'bg-info');
                                @endphp
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar {{ $colorClase }}" 
                                         role="progressbar" 
                                         style="width: {{ $porcentaje }}%"
                                         aria-valuenow="{{ $porcentaje }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <strong>{{ $porcentaje }}%</strong>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No hay datos disponibles para el período seleccionado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($ranking->isNotEmpty())
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <td colspan="3" class="text-right">TOTAL:</td>
                            <td class="text-center">
                                <span class="badge badge-dark badge-lg">{{ $ranking->sum('total_solicitudes') }}</span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.4rem 0.6rem;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const usuarios = @json($ranking->pluck('solicitante.nombre_completo'));
    const solicitudes = @json($ranking->pluck('total_solicitudes'));

    // Gradient colors
    const ctx = document.getElementById('chartRankingUsuarios').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(255, 193, 7, 0.8)');
    gradient.addColorStop(0.5, 'rgba(23, 162, 184, 0.8)');
    gradient.addColorStop(1, 'rgba(40, 167, 69, 0.8)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: usuarios,
            datasets: [{
                label: 'Solicitudes',
                data: solicitudes,
                backgroundColor: gradient,
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { size: 12 }
                    }
                },
                x: {
                    ticks: {
                        font: { size: 11 }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Usuarios Más Activos',
                    font: { size: 16, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Solicitudes: ' + context.parsed.y;
                        }
                    }
                }
            }
        }
    });
</script>
@stop