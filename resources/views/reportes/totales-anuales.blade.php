@extends('adminlte::page')

@section('title', 'Totales Anuales')

@section('content_header')
    <h1><i class="fas fa-chart-area"></i> Totales Anuales - Comparativa</h1>
@stop

@section('content')
    {{-- Resumen de Años --}}
    <div class="row">
        @foreach($ticketsPorAnio as $anio => $data)
            <div class="col-lg-2 col-6">
                <div class="small-box bg-gradient-{{ $loop->index % 2 == 0 ? 'primary' : 'info' }}">
                    <div class="inner">
                        <h3>{{ $data['total'] }}</h3>
                        <p>Año {{ $anio }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <a href="{{ route('reportes.totales-mensuales', ['anio' => $anio]) }}" class="small-box-footer">
                        Ver detalle <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Gráfico Comparativo Principal --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-bar"></i> Comparativa de Años
            </h3>
            <div class="card-tools">
                @can('reports.export')
                    <a href="{{ route('reportes.exportar-pdf', 'totales-anuales') }}" 
                       class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <canvas id="chartComparativaAnual" height="80"></canvas>
        </div>
    </div>

    {{-- Gráficos Secundarios --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line"></i> Tendencia de Crecimiento</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartTendencia"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Distribución de Estados</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartEstadosAnual"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla Comparativa --}}
    <div class="card">
        <div class="card-header bg-indigo">
            <h3 class="card-title"><i class="fas fa-table"></i> Tabla Comparativa</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="bg-light">
                    <tr>
                        <th>Año</th>
                        <th class="text-center">Total Tickets</th>
                        <th class="text-center">Finalizados</th>
                        <th class="text-center">Pendientes</th>
                        <th class="text-center">% Finalización</th>
                        <th class="text-center">Variación</th>
                        <th width="250px">Progreso</th>
                    </tr>
                </thead>
                <tbody>
                    @php $anioAnterior = null; @endphp
                    @foreach($ticketsPorAnio as $anio => $data)
                        @php
                            $porcentaje = $data['total'] > 0 ? round(($data['finalizados'] / $data['total']) * 100, 1) : 0;
                            
                            // Calcular variación respecto al año anterior
                            $variacion = 0;
                            $variacionTexto = '-';
                            $variacionClase = 'text-muted';
                            
                            if ($anioAnterior !== null && isset($ticketsPorAnio[$anioAnterior])) {
                                $totalAnterior = $ticketsPorAnio[$anioAnterior]['total'];
                                if ($totalAnterior > 0) {
                                    $variacion = round((($data['total'] - $totalAnterior) / $totalAnterior) * 100, 1);
                                    $variacionTexto = ($variacion > 0 ? '+' : '') . $variacion . '%';
                                    $variacionClase = $variacion > 0 ? 'text-success' : ($variacion < 0 ? 'text-danger' : 'text-muted');
                                }
                            }
                            $anioAnterior = $anio;
                        @endphp
                        <tr>
                            <td>
                                <strong class="text-primary">
                                    <i class="fas fa-calendar-alt"></i> {{ $anio }}
                                </strong>
                            </td>
                            <td class="text-center">
                                <h5>
                                    <span class="badge badge-primary">{{ $data['total'] }}</span>
                                </h5>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success badge-lg">{{ $data['finalizados'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning badge-lg">{{ $data['pendientes'] }}</span>
                            </td>
                            <td class="text-center">
                                <strong>{{ $porcentaje }}%</strong>
                            </td>
                            <td class="text-center">
                                <span class="{{ $variacionClase }} font-weight-bold">
                                    @if($variacion > 0)
                                        <i class="fas fa-arrow-up"></i>
                                    @elseif($variacion < 0)
                                        <i class="fas fa-arrow-down"></i>
                                    @else
                                        <i class="fas fa-minus"></i>
                                    @endif
                                    {{ $variacionTexto }}
                                </span>
                            </td>
                            <td>
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
                    @endforeach
                </tbody>
                <tfoot class="bg-light font-weight-bold">
                    <tr>
                        <td>TOTAL GENERAL</td>
                        <td class="text-center">
                            <h5>
                                <span class="badge badge-dark">{{ collect($ticketsPorAnio)->sum('total') }}</span>
                            </h5>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success badge-lg">{{ collect($ticketsPorAnio)->sum('finalizados') }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-warning badge-lg">{{ collect($ticketsPorAnio)->sum('pendientes') }}</span>
                        </td>
                        <td class="text-center">
                            @php
                                $totalGeneral = collect($ticketsPorAnio)->sum('total');
                                $finalizadosGeneral = collect($ticketsPorAnio)->sum('finalizados');
                                $porcentajeGeneral = $totalGeneral > 0 ? round(($finalizadosGeneral / $totalGeneral) * 100, 1) : 0;
                            @endphp
                            <strong>{{ $porcentajeGeneral }}%</strong>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Análisis y Observaciones --}}
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-line"></i> Análisis de Tendencias</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Año con Más Solicitudes</span>
                            <span class="info-box-number">
                                {{ collect($ticketsPorAnio)->sortByDesc('total')->keys()->first() }} 
                                ({{ collect($ticketsPorAnio)->max('total') }})
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Mayor % de Finalización</span>
                            <span class="info-box-number">
                                @php
                                    $mejorAnio = collect($ticketsPorAnio)->map(function($data, $anio) {
                                        return [
                                            'anio' => $anio,
                                            'porcentaje' => $data['total'] > 0 ? round(($data['finalizados'] / $data['total']) * 100, 1) : 0
                                        ];
                                    })->sortByDesc('porcentaje')->first();
                                @endphp
                                {{ $mejorAnio['anio'] ?? 'N/A' }} ({{ $mejorAnio['porcentaje'] ?? 0 }}%)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box bg-gradient-warning">
                        <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Promedio Anual</span>
                            <span class="info-box-number">
                                {{ count($ticketsPorAnio) > 0 ? round(collect($ticketsPorAnio)->avg('total'), 1) : 0 }} tickets
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> Observaciones:</h5>
                <ul>
                    <li>El análisis comprende los últimos {{ count($ticketsPorAnio) }} años de actividad del sistema.</li>
                    <li>La tendencia general muestra {{ collect($ticketsPorAnio)->last()['total'] > collect($ticketsPorAnio)->first()['total'] ? 'un crecimiento' : 'una disminución' }} en la cantidad de solicitudes.</li>
                    <li>El promedio de finalización general es del {{ $porcentajeGeneral }}%.</li>
                </ul>
            </div>
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
    // Datos
    const anios = @json(array_keys($ticketsPorAnio));
    const totales = @json(collect($ticketsPorAnio)->pluck('total')->values());
    const finalizados = @json(collect($ticketsPorAnio)->pluck('finalizados')->values());
    const pendientes = @json(collect($ticketsPorAnio)->pluck('pendientes')->values());

    // Colores degradados
    const coloresPrimary = anios.map((_, index) => {
        const opacity = 0.5 + (index * 0.5 / anios.length);
        return `rgba(0, 123, 255, ${opacity})`;
    });

    const coloresSuccess = anios.map((_, index) => {
        const opacity = 0.5 + (index * 0.5 / anios.length);
        return `rgba(40, 167, 69, ${opacity})`;
    });

    const coloresWarning = anios.map((_, index) => {
        const opacity = 0.5 + (index * 0.5 / anios.length);
        return `rgba(255, 193, 7, ${opacity})`;
    });

    // Gráfico de barras - Comparativa
    const ctxBarra = document.getElementById('chartComparativaAnual').getContext('2d');
    new Chart(ctxBarra, {
        type: 'bar',
        data: {
            labels: anios,
            datasets: [
                {
                    label: 'Total',
                    data: totales,
                    backgroundColor: coloresPrimary,
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2
                },
                {
                    label: 'Finalizados',
                    data: finalizados,
                    backgroundColor: coloresSuccess,
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2
                },
                {
                    label: 'Pendientes',
                    data: pendientes,
                    backgroundColor: coloresWarning,
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 10 }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Comparativa de Tickets por Año',
                    font: { size: 16 }
                }
            }
        }
    });

    // Gráfico de línea - Tendencia
    const ctxTendencia = document.getElementById('chartTendencia').getContext('2d');
    const gradientTendencia = ctxTendencia.createLinearGradient(0, 0, 0, 400);
    gradientTendencia.addColorStop(0, 'rgba(40, 167, 69, 0.4)');
    gradientTendencia.addColorStop(1, 'rgba(40, 167, 69, 0.0)');

    new Chart(ctxTendencia, {
        type: 'line',
        data: {
            labels: anios,
            datasets: [{
                label: 'Total de Tickets',
                data: totales,
                borderColor: 'rgba(40, 167, 69, 1)',
                backgroundColor: gradientTendencia,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: 'rgba(40, 167, 69, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 10 }
                }
            },
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Evolución en el Tiempo',
                    font: { size: 14 }
                }
            }
        }
    });

    // Gráfico de pastel - Estados general
    const totalFinalizadosGeneral = finalizados.reduce((a, b) => a + b, 0);
    const totalPendientesGeneral = pendientes.reduce((a, b) => a + b, 0);

    const ctxPastel = document.getElementById('chartEstadosAnual').getContext('2d');
    new Chart(ctxPastel, {
        type: 'doughnut',
        data: {
            labels: ['Finalizados', 'Pendientes'],
            datasets: [{
                data: [totalFinalizadosGeneral, totalPendientesGeneral],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)'
                ],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: { size: 12 }
                    }
                },
                title: {
                    display: true,
                    text: 'Estados de Todos los Años',
                    font: { size: 14 }
                }
            }
        }
    });
</script>
@stop