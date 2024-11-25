<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Periksa apakah session 'user' ada
        if (!Session::has('user')) {
            return redirect()->route('login.form')->withErrors(['login_error' => 'Silakan login terlebih dahulu']);
        }

        // Lanjutkan request
        return $next($request);
    }
}
