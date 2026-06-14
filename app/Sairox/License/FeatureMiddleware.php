<?php

namespace App\Sairox\License;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeatureMiddleware
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (! app(LicenseService::class)->hasFeature($feature)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This feature requires a valid license key.',
                    'upgrade_url' => 'https://sairox.com/pricing',
                ], 402);
            }

            abort(402, 'This feature requires a valid license key. <a href="https://sairox.com/pricing">Upgrade here</a>.');
        }

        return $next($request);
    }
}
