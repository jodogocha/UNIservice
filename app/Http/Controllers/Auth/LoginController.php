<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Si ya está autenticado, redirigir al home
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validar que se proporcione el campo login (email o documento)
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ], [
            'login.required' => 'El email o número de documento es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        // Determinar si el login es un email o un número de documento
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'documento';

        // Preparar las credenciales para la autenticación
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        // Intentar autenticar
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Verificar si el usuario está activo
            if (!Auth::user()->activo) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Tu cuenta está inactiva. Contacta al administrador.',
                ])->onlyInput('login');
            }

            // Registrar en auditoría
            AuditLog::log(
                'login',
                'authentication',
                'Inicio de sesión exitoso',
                auth()->id()
            );

            // Redirigir siempre al home después del login exitoso
            return redirect()->route('home')->with('success', '¡Bienvenido ' . Auth::user()->nombre_completo . '!');
        }

        // Registrar intento fallido
        AuditLog::log(
            'login-failed',
            'authentication',
            'Intento de inicio de sesión fallido para: ' . $request->login
        );

        return back()->withErrors([
            'login' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        // Registrar logout antes de cerrar sesión
        AuditLog::log(
            'logout',
            'authentication',
            'Cierre de sesión',
            auth()->id()
        );
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}