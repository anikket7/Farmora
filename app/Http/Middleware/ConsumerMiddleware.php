<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConsumerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isConsumer()) {
            abort(403, 'Access denied. Consumer account required.');
        }

        return $next($request);
    }
}
