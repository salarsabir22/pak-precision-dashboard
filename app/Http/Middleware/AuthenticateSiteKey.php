<?php

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;

class AuthenticateSiteKey
{
    public function handle(Request $request, Closure $next)
    {
        $plain = $request->header('X-Site-Key') ?: $request->bearerToken();

        if (! is_string($plain) || $plain === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Missing site API key. Send it as X-Site-Key.',
            ], 401);
        }

        $site = Site::query()
            ->where('api_key_hash', Site::hashKey($plain))
            ->where('is_active', true)
            ->first();

        if (! $site) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid or inactive site API key.',
            ], 401);
        }

        $request->attributes->set('site', $site);

        return $next($request);
    }
}
