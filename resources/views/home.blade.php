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
                <strong>{{ $user->nombre_completo }}</strong><br>
                <small>
                    Dependencia: {{ $user->dependencia->nombre ?? 'N/A' }}<br>
                    Rol: {{ $user->roles->first()->nombre ?? 'Sin rol asignado' }}
                </small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-ticket-alt mr-2"></i>
                        Sistema de Gestión de Tickets de Servicio
                    </h3>
                </div>
                <div class="card-body">
                    <h4>Bienvenido a UNIservice</h4>
                    <p><strong>Facultad de Humanidades, Ciencias Sociales y Cultura Guaraní</strong></p>
                    <p>Universidad Nacional de Itapúa</p>
                    <hr>
                    <p class="text-muted">
                        Sistema para la gestión de pedidos de servicio del Departamento de TICs
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>0</h3>
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
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>0</h3>
                    <p>Tickets Finalizados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>0</h3>
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
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>0</h3>
                    <p>Usuarios Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Estilos CSS personalizados si los necesitas --}}
    <style>
        .card-header {
            background-color: #007bff;
            color: white;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('UNIservice - Dashboard cargado correctamente');
    </script>
@stop