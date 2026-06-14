<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');

        if (! $apiKey || $apiKey !== get_option('api_key')) {
            return response()->json(['error' => 'Invalid or missing API key.'], 401);
        }

        return $next($request);
    }
}
