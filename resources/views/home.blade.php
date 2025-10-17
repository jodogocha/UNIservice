@extends('adminlte::page')

@section('title', 'Dashboard - UNIservice')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Bienvenido!</h5>
                <strong>{{ $user->nombre_completo ?? 'Usuario' }}</strong><br>
                <small>
                    Dependencia: {{ $user->dependencia->nombre ?? 'N/A' }}<br>
                    Rol: {{ $user->roles->first()->nombre ?? 'Sin rol asignado' }}
                </small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $ticketsPendientes ?? 0 }}</h3>
                    <p>Tickets Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $ticketsEnProceso ?? 0 }}</h3>
                    <p>En Proceso</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wrench"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $ticketsListos ?? 0 }}</h3>
                    <p>Listos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $ticketsFinalizados ?? 0 }}</h3>
                    <p>Finalizados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        Sistema funcionando correctamente
                    </h3>
                </div>
                <div class="card-body">
                    <p>El sistema ha cargado correctamente.</p>
                    <p><strong>Usuario autenticado:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Fecha y hora:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .small-box .icon {
            font-size: 70px;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('UNIservice - Dashboard cargado correctamente');
    </script>
@stop