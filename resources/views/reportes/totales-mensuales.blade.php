@extends('adminlte::page')

@section('title', 'Totales Mensuales')

@section('content_header')
    <h1><i class="fas fa-chart-line"></i> Totales Mensuales</h1>
@stop

@section('content')
    {{-- Filtro de Año --}}
    <div class="card">
        <div class="card-header bg-teal">
            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Seleccionar Año</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reportes.totales-mensuales') }}" method="GET">
                <div class="row">
                    <div class="col-md-10">
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
                        <button type="submit" class="btn btn-teal btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Gráfico Principal --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-line"></i> Evolución Mensual {{ $anio }}
            </h3>
            <div class="card-tools">
                @can('reports.export')
                    <a href="{{ route('reportes.exportar-pdf', 'totales-mensuales') }}?anio={{ $anio }}" 
                       class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <canvas id="chartTotalesMensuales" height="80"></canvas>
        </div>
    </div>

    {{-- Gráficos Secundarios --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Por Tipo de Servicio</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartTipoServicio"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Por Prioridad</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartPrioridad"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Datos --}}
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title"><i class="fas fa-table"></i> Detalle Mensual {{ $anio }}</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="bg-light">
                    <tr>
                        <th>Mes</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Finalizados</th>
                        <th class="text-center">Pendientes</th>
                        <th class="text-center">% Finalización</th>
                        <th width="250px">Progreso</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totalAnual = 0;
                        $finalizadosAnual = 0;
                        $pendientesAnual = 0;
                    @endphp
                    @foreach($ticketsPorMes as $mes => $data)
                        @php
                            $totalAnual += $data['total'];
                            $finalizadosAnual += $data['finalizados'];
                            $pendientesAnual += $data['pendientes'];
                            $porcentaje = $data['total'] > 0 ? round(($data['finalizados'] / $data['total']) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td><strong>{{ $data['mes'] }}</strong></td>
                            <td class="text-center">
                                <span class="badge badge-primary badge-lg">{{ $data['total'] }}</span>
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
                        <td>TOTAL {{ $anio }}</td>
                        <td class="text-center">
                            <span class="badge badge-dark badge-lg">{{ $totalAnual }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success badge-lg">{{ $finalizadosAnual }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-warning badge-lg">{{ $pendientesAnual }}</span>
                        </td>
                        <td class="text-center">
                            <strong>{{ $totalAnual > 0 ? round(($finalizadosAnual / $totalAnual) * 100, 1) : 0 }}%</strong>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
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
    // Datos mensuales
    const meses = @json(collect($ticketsPorMes)->pluck('mes'));
    const totales = @json(collect($ticketsPorMes)->pluck('total'));
    const finalizados = @json(collect($ticketsPorMes)->pluck('finalizados'));
    const pendientes = @json(collect($ticketsPorMes)->pluck('pendientes'));

    // Gráfico de líneas - Evolución mensual
    const ctxLinea = document.getElementById('chartTotalesMensuales').getContext('2d');
    new Chart(ctxLinea, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [
                {
                    label: 'Total',
                    data: totales,
                    borderColor: 'rgba(0, 123, 255, 1)',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Finalizados',
                    data: finalizados,
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Pendientes',
                    data: pendientes,
                    borderColor: 'rgba(255, 193, 7, 1)',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Tickets por Mes - Año {{ $anio }}',
                    font: { size: 16 }
                }
            }
        }
    });

    // Gráfico de pastel - Tipo de Servicio
    const tiposServicio = @json($totalesPorTipo->pluck('tipo_servicio'));
    const cantidadesTipo = @json($totalesPorTipo->pluck('total'));

    const ctxTipo = document.getElementById('chartTipoServicio').getContext('2d');
    new Chart(ctxTipo, {
        type: 'doughnut',
        data: {
            labels: tiposServicio.map(t => {
                const tipos = {
                    'mantenimiento': 'Mantenimiento',
                    'asesoramiento': 'Asesoramiento',
                    'reparacion': 'Reparación',
                    'configuracion': 'Configuración',
                    'otro': 'Otro'
                };
                return tipos[t] || t;
            }),
            datasets: [{
                data: cantidadesTipo,
                backgroundColor: [
                    'rgba(0, 123, 255, 0.8)',
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)',
                    'rgba(108, 117, 125, 0.8)'
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
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de pastel - Prioridad
    const prioridades = @json($totalesPorPrioridad->pluck('prioridad'));
    const cantidadesPrioridad = @json($totalesPorPrioridad->pluck('total'));

    const ctxPrioridad = document.getElementById('chartPrioridad').getContext('2d');
    new Chart(ctxPrioridad, {
        type: 'doughnut',
        data: {
            labels: prioridades.map(p => {
                const priors = {
                    'baja': 'Baja',
                    'media': 'Media',
                    'alta': 'Alta',
                    'urgente': 'Urgente'
                };
                return priors[p] || p;
            }),
            datasets: [{
                data: cantidadesPrioridad,
                backgroundColor: [
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(253, 126, 20, 0.8)',
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
                    position: 'bottom'
                }
            }
        }
    });
</script>
@stop