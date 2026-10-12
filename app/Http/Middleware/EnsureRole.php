<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Uso: ->middleware('role:admin') ou ->middleware('role:user')
     * Se o role não corresponder, redireciona para a área do próprio utilizador.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role->value !== $role) {
            return redirect()->route($user->homeRoute());
        }

        return $next($request);
    }
}
