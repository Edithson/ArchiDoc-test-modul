<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperPrivileged
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isSuper()) {
            abort(403, 'Accès refusé. Cette section est réservée aux utilisateurs ayant le rôle Super Privilégié.');
        }

        return $next($request);
    }
}
