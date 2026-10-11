<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()?->isSuperAdmin()) {
            abort(403, 'This action is limited to the superadmin.');
        }

        return $next($request);
    }
}
