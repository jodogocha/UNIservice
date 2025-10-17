@extends('adminlte::master')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('adminlte_css_pre')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('classes_body', 'login-page')

@section('body')
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-body">
                {{-- Logo --}}
                <div class="text-center mb-3">
                    <img src="{{ asset('images/logos/humanidades.png') }}" 
                         alt="Logo Facultad de Humanidades" 
                         class="img-circle elevation-3"
                         style="width: 150px; height: 150px; object-fit: cover;">
                </div>

                {{-- Título --}}
                <h4 class="text-center mb-3">Iniciar Sesión</h4>

                {{-- Mensajes de éxito --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Descripción --}}
                <p class="login-box-msg">Ingrese sus credenciales para continuar</p>

                {{-- Formulario --}}
                <form action="{{ route('login') }}" method="post">
                    @csrf

                    {{-- Email field --}}
                    <div class="input-group mb-3">
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" 
                               placeholder="Email" 
                               autofocus 
                               required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Password field --}}
                    <div class="input-group mb-3">
                        <input type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Contraseña"
                               required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Remember me y botón de login --}}
                    <div class="row">
                        <div class="col-7">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">
                                    Recordarme
                                </label>
                            </div>
                        </div>
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary btn-block">
                                <span class="fas fa-sign-in-alt"></span>
                                Ingresar
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Footer del card --}}
                <hr>
                <p class="text-center mb-0">
                    <small class="text-muted">
                        <strong>UNIservice</strong><br>
                        Facultad de Humanidades, Ciencias Sociales y Cultura Guaraní<br>
                        Universidad Nacional de Itapúa
                    </small>
                </p>
            </div>
        </div>
    </div>
@stop

@section('adminlte_css')
    <style>
        body.login-page {
            background-color: #e9ecef;
        }
        
        .login-box {
            width: 400px;
            margin: 7% auto;
        }
        
        .login-box .card {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .login-box-msg {
            margin: 0 0 20px 0;
            text-align: center;
            padding: 0 20px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .login-box {
                width: 90%;
                margin-top: 5%;
            }
        }
    </style>
@stop

@section('adminlte_js')
    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });
    </script>
@stop