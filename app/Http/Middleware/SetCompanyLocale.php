<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SetCompanyLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Superadmin uses Spanish by default
            if ($user->isSuperAdmin()) {
                App::setLocale('es');
                Carbon::setLocale('es');
                return $next($request);
            }
            
            // Get company language
            if ($user->company) {
                $language = $user->company->getLanguage();
                App::setLocale($language);
                Carbon::setLocale($language);
            } else {
                // Default to Spanish if no company
                App::setLocale('es');
                Carbon::setLocale('es');
            }
        } else {
            // Default to Spanish for guests
            App::setLocale('es');
            Carbon::setLocale('es');
        }

        return $next($request);
    }
}
