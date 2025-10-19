@extends('adminlte::page')

@section('title', 'Solicitudes por Dependencia')

@section('content_header')
    <h1><i class="fas fa-building"></i> Solicitudes por Dependencia</h1>
@stop

@section('content')
    {{-- Filtros --}}
    <div class="card">
        <div class="card-header bg-success">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reportes.solicitudes-dependencia') }}" method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="mes">Mes:</label>
                            <select class="form-control" id="mes" name="mes">
                                <option value="">Todos</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(null, $m, 1)->locale('es')->monthName }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="anio">Año:</label>
                            <select class="form-control" id="anio" name="anio">
                                @for($a = now()->year; $a >= now()->year - 5; $a--)
                                    <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>
                                        {{ $a }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Gráfico --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-bar"></i> Gráfico de Solicitudes
            </h3>
        </div>
        <div class="card-body">
            <canvas id="chartSolicitudesDependencia" height="80"></canvas>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title"><i class="fas fa-table"></i> Detalle por Dependencia</h3>
            <div class="card-tools">
                @can('reports.export')
                    <a href="{{ route('reportes.exportar-pdf', 'solicitudes-dependencia') }}?mes={{ $mes }}&anio={{ $anio }}" 
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
                        <th>#</th>
                        <th>Dependencia</th>
                        <th>Código</th>
                        <th class="text-center">Total Solicitudes</th>
                        <th width="250px">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalGeneral = $solicitudes->sum('total'); @endphp
                    @forelse($solicitudes as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $item->dependencia->nombre }}</strong></td>
                            <td><code>{{ $item->dependencia->codigo }}</code></td>
                            <td class="text-center">
                                <span class="badge badge-primary badge-lg">{{ $item->total }}</span>
                            </td>
                            <td>
                                @php
                                    $porcentaje = $totalGeneral > 0 ? round(($item->total / $totalGeneral) * 100, 1) : 0;
                                @endphp
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" 
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
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No hay datos disponibles</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($solicitudes->isNotEmpty())
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <td colspan="3" class="text-right">TOTAL:</td>
                            <td class="text-center">
                                <span class="badge badge-dark badge-lg">{{ $totalGeneral }}</span>
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
        .badge-lg { font-size: 1rem; padding: 0.4rem 0.6rem; }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const dependencias = @json($solicitudes->pluck('dependencia.nombre'));
    const totales = @json($solicitudes->pluck('total'));

    const ctx = document.getElementById('chartSolicitudesDependencia').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dependencias,
            datasets: [{
                label: 'Solicitudes',
                data: totales,
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 2
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
                    text: 'Solicitudes por Dependencia',
                    font: { size: 16 }
                }
            }
        }
    });
</script>
@stop