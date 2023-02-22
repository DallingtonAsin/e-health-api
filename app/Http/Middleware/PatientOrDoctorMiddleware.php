<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PatientOrDoctorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard('patient')->check() || auth()->guard('doctor')->check()) {
            return $next($request);
        }

        return response('Unauthorized.', 401);
    }
}
