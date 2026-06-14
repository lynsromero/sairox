<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PageCache
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') || auth()->check() || $request->is('admin/*', 'api/*')) {
            return $next($request);
        }

        $key = 'page_cache:'.md5($request->fullUrl());
        $cached = Cache::get($key);

        if ($cached) {
            return response($cached['content'], 200, $cached['headers']);
        }

        $response = $next($request);

        if ($response->status() === 200) {
            Cache::put($key, [
                'content' => $response->getContent(),
                'headers' => ['Content-Type' => 'text/html; charset=UTF-8'],
            ], now()->addHours(24));
        }

        return $response;
    }
}
