@extends('adminlte::page')

@section('title', 'Trabajos por Usuario')

@section('content_header')
    <h1><i class="fas fa-user-check"></i> Trabajos Realizados por Usuario</h1>
@stop

@section('content')
    {{-- Filtros --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reportes.trabajos-usuario') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mes">Mes:</label>
                            <select class="form-control" id="mes" name="mes">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(null, $m, 1)->locale('es')->monthName }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Gráfico de Barras --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> 
                        Gráfico de Trabajos - {{ \Carbon\Carbon::create(null, $mes, 1)->locale('es')->monthName }} {{ $anio }}
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartTrabajosPorUsuario" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Datos --}}
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title"><i class="fas fa-table"></i> Detalle de Trabajos</h3>
            <div class="card-tools">
                @can('reports.export')
                    <a href="{{ route('reportes.exportar-pdf', 'trabajos-usuario') }}?mes={{ $mes }}&anio={{ $anio }}" 
                       class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Técnico</th>
                        <th class="text-center">Total Asignados</th>
                        <th class="text-center">Finalizados</th>
                        <th class="text-center">En Proceso</th>
                        <th class="text-center">% Completado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trabajosRealizados as $index => $trabajo)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $trabajo['tecnico'] }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info badge-lg">{{ $trabajo['total'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success badge-lg">{{ $trabajo['finalizados'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning badge-lg">{{ $trabajo['en_proceso'] }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $porcentaje = $trabajo['total'] > 0 ? round(($trabajo['finalizados'] / $trabajo['total']) * 100, 1) : 0;
                                @endphp
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" 
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
                            <td colspan="6" class="text-center">
                                <p class="py-3">No hay datos disponibles para el período seleccionado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="2">TOTAL</td>
                        <td class="text-center">{{ collect($trabajosRealizados)->sum('total') }}</td>
                        <td class="text-center">{{ collect($trabajosRealizados)->sum('finalizados') }}</td>
                        <td class="text-center">{{ collect($trabajosRealizados)->sum('en_proceso') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
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
    // Datos para el gráfico
    const tecnicos = @json(collect($trabajosRealizados)->pluck('tecnico'));
    const totales = @json(collect($trabajosRealizados)->pluck('total'));
    const finalizados = @json(collect($trabajosRealizados)->pluck('finalizados'));
    const enProceso = @json(collect($trabajosRealizados)->pluck('en_proceso'));

    // Configuración del gráfico
    const ctx = document.getElementById('chartTrabajosPorUsuario').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: tecnicos,
            datasets: [
                {
                    label: 'Total Asignados',
                    data: totales,
                    backgroundColor: 'rgba(23, 162, 184, 0.8)',
                    borderColor: 'rgba(23, 162, 184, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Finalizados',
                    data: finalizados,
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'En Proceso',
                    data: enProceso,
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Trabajos Realizados por Técnico',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });
</script>
@stop