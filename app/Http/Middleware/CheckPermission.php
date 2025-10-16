<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        if ($request->user()->hasPermission($permission)) {
            return $next($request);
        }

        abort(403, 'No tienes permisos para realizar esta acción.');
    }
}