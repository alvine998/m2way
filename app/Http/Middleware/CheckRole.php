<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userRole = auth()->user()?->role?->slug;

        if (!auth()->check() || !in_array($userRole, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
