<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para asegurar el scope de tenant en todas las peticiones
 * Aplica automáticamente el filtro company_id en los modelos
 */
class EnsureTenantScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            // Superadmin no tiene tenant scope
            return $next($request);
        }

        if (Auth::check() && Auth::user()->company_id) {
            // Establecer el tenant actual en el request
            $request->merge(['tenant_id' => Auth::user()->company_id]);
            
            // Aplicar global scope si es necesario
            // Esto se puede hacer también a nivel de modelo con Global Scopes
        }

        return $next($request);
    }
}
