<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    /**
     * Handle an incoming request and enforce dynamic session timeout based on settings.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $maxTimeoutMinutes = (int) setting('securite.session_timeout_minutes', 120);
            $lastActivity = session('last_activity_time');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity) > ($maxTimeoutMinutes * 60)) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => "Votre session a expiré suite à une inactivité de plus de {$maxTimeoutMinutes} minutes.",
                ]);
            }

            session(['last_activity_time' => $currentTime]);
        }

        return $next($request);
    }
}
