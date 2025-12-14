<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Este middleware es principalmente para rutas web
        // La validación real se hace en el frontend con localStorage
        // Por lo que simplemente dejamos pasar para que el frontend maneje la autenticación
        return $next($request);
    }
}
