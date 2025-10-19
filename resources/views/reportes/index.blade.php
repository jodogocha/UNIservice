@extends('adminlte::page')

@section('title', 'Reportes')

@section('content_header')
    <h1><i class="fas fa-chart-bar"></i> Centro de Reportes</h1>
@stop

@section('content')
    {{-- Estadísticas Generales --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_tickets'] }}</h3>
                    <p>Total de Tickets</p>
                </div>
                <div class="icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $stats['tickets_mes_actual'] }}</h3>
                    <p>Tickets Este Mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['tickets_resueltos'] }}</h3>
                    <p>Tickets Resueltos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['tickets_pendientes'] }}</h3>
                    <p>Tickets Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Menú de Reportes --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Reportes Disponibles
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Trabajos por Usuario --}}
                        <div class="col-md-4">
                            <div class="card card-widget widget-user-2">
                                <div class="widget-user-header bg-gradient-info">
                                    <div class="widget-user-image">
                                        <i class="fas fa-user-check fa-3x"></i>
                                    </div>
                                    <h3 class="widget-user-username">Trabajos por Usuario</h3>
                                    <h5 class="widget-user-desc">Reporte mensual de trabajos realizados</h5>
                                </div>
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('reportes.trabajos-usuario') }}" class="nav-link">
                                                Ver Reporte <span class="float-right badge bg-info"><i class="fas fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Solicitudes por Dependencia --}}
                        <div class="col-md-4">
                            <div class="card card-widget widget-user-2">
                                <div class="widget-user-header bg-gradient-success">
                                    <div class="widget-user-image">
                                        <i class="fas fa-building fa-3x"></i>
                                    </div>
                                    <h3 class="widget-user-username">Por Dependencia</h3>
                                    <h5 class="widget-user-desc">Solicitudes por dependencia</h5>
                                </div>
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('reportes.solicitudes-dependencia') }}" class="nav-link">
                                                Ver Reporte <span class="float-right badge bg-success"><i class="fas fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Ranking Dependencias --}}
                        <div class="col-md-4">
                            <div class="card card-widget widget-user-2">
                                <div class="widget-user-header bg-gradient-warning">
                                    <div class="widget-user-image">
                                        <i class="fas fa-trophy fa-3x"></i>
                                    </div>
                                    <h3 class="widget-user-username">Ranking Dependencias</h3>
                                    <h5 class="widget-user-desc">Las que más solicitan servicios</h5>
                                </div>
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('reportes.ranking-dependencias') }}" class="nav-link">
                                                Ver Reporte <span class="float-right badge bg-warning"><i class="fas fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Ranking Usuarios --}}
                        <div class="col-md-4">
                            <div class="card card-widget widget-user-2">
                                <div class="widget-user-header bg-gradient-danger">
                                    <div class="widget-user-image">
                                        <i class="fas fa-medal fa-3x"></i>
                                    </div>
                                    <h3 class="widget-user-username">Ranking Usuarios</h3>
                                    <h5 class="widget-user-desc">Usuarios que más solicitan</h5>
                                </div>
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('reportes.ranking-usuarios') }}" class="nav-link">
                                                Ver Reporte <span class="float-right badge bg-danger"><i class="fas fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Servicios por Horario --}}
                        <div class="col-md-4">
                            <div class="card card-widget widget-user-2">
                                <div class="widget-user-header bg-gradient-primary">
                                    <div class="widget-user-image">
                                        <i class="fas fa-clock fa-3x"></i>
                                    </div>
                                    <h3 class="widget-user-username">Por Horario</h3>
                                    <h5 class="widget-user-desc">Servicios en franja horaria</h5>
                                </div>
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('reportes.servicios-horario') }}" class="nav-link">
                                                Ver Reporte <span class="float-right badge bg-primary"><i class="fas fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Trabajos Asignados --}}
                        <div class="col-md-4">
                            <div class="card card-widget widget-user-2">
                                <div class="widget-user-header bg-gradient-secondary">
                                    <div class="widget-user-image">
                                        <i class="fas fa-tasks fa-3x"></i>
                                    </div>
                                    <h3 class="widget-user-username">Trabajos Asignados</h3>
                                    <h5 class="widget-user-desc">Por usuario técnico</h5>
                                </div>
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('reportes.trabajos-asignados') }}" class="nav-link">
                                                Ver Reporte <span class="float-right badge bg-secondary"><i class="fas fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Totales Mensuales --}}
                        <div class="col-md-4">
                            <div class="card card-widget widget-user-2">
                                <div class="widget-user-header bg-gradient-teal">
                                    <div class="widget-user-image">
                                        <i class="fas fa-chart-line fa-3x"></i>
                                    </div>
                                    <h3 class="widget-user-username">Totales Mensuales</h3>
                                    <h5 class="widget-user-desc">Estadísticas mensuales del año</h5>
                                </div>
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('reportes.totales-mensuales') }}" class="nav-link">
                                                Ver Reporte <span class="float-right badge bg-teal"><i class="fas fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Totales Anuales --}}
                        <div class="col-md-4">
                            <div class="card card-widget widget-user-2">
                                <div class="widget-user-header bg-gradient-indigo">
                                    <div class="widget-user-image">
                                        <i class="fas fa-chart-area fa-3x"></i>
                                    </div>
                                    <h3 class="widget-user-username">Totales Anuales</h3>
                                    <h5 class="widget-user-desc">Comparativa de años</h5>
                                </div>
                                <div class="card-footer p-0">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('reportes.totales-anuales') }}" class="nav-link">
                                                Ver Reporte <span class="float-right badge bg-indigo"><i class="fas fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
<script>
    console.log('Centro de Reportes cargado');
</script>
@stop