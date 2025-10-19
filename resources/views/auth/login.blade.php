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
                    <img src="{{ asset('images/logos/uni.png') }}" 
                         alt="Logo de la UNI" 
                         class="img-fluid" 
                         style="max-width: 150px;"> {{-- ← QUITAR img-circle, CAMBIAR A img-fluid --}}
                </div>

                {{-- Título --}}
                <h4 class="login-box-msg"><b>UNI</b>Service</h4>
                <p class="login-box-msg">Inicia sesión para continuar</p>

                {{-- Mensajes de error y éxito --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        @foreach ($errors->all() as $error)
                            <p class="mb-0"><i class="icon fas fa-ban"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <p class="mb-0"><i class="icon fas fa-check"></i> {{ session('success') }}</p>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <p class="mb-0"><i class="icon fas fa-info"></i> {{ session('status') }}</p>
                    </div>
                @endif

                {{-- Formulario de Login --}}
                <form action="{{ route('login') }}" method="post">
                    @csrf

                    {{-- Email o Documento--}}
                    <div class="input-group mb-3">
                        <input type="Text" 
                               name="login" 
                               class="form-control @error('login') is-invalid @enderror" 
                               value="{{ old('login') }}" 
                               placeholder="Email o Número de Documento"
                               required 
                               autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('login')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
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
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">
                                    Recordarme
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Información adicional --}}
                    <hr>
                    <div class="text-center text-muted">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Puedes iniciar sesión con tu <strong>email</strong> o <strong>número de documento</strong>
                        </small>
                    </div>

                    {{-- Submit Button --}}
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </button>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Universidad Nacional de Itapúa &copy; {{ date('Y') }}
                        </small>
                    </div>
                </form>

                @if(session('status'))
                    <div class="alert alert-success mt-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script>
        $(document).ready(function() {
            // Auto-ocultar alertas después de 5 segundos
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@stop