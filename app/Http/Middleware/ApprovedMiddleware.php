<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprovedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isApproved()) {
            return redirect()->route('pending.approval')
                ->with('warning', 'Your account is pending admin approval.');
        }

        return $next($request);
    }
}
