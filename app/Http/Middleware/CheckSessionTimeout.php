<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $maxIdleSeconds = (int) env('SESSION_TIMEOUT_SECONDS', 900); // Default 15 minutes idle timeout
            $lastActivity = session('last_activity_time');

            if ($lastActivity && (time() - $lastActivity > $maxIdleSeconds)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Your session has expired due to inactivity. Please sign in again.');
            }

            session(['last_activity_time' => time()]);
        }

        return $next($request);
    }
}
