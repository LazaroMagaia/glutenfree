<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->isCustomer()) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}