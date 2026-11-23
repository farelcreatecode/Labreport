<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     * Ensures admin is authenticated with token in session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('admin_token')) {
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah berakhir atau Anda belum login sebagai Administrator.');
        }

        return $next($request);
    }
}
