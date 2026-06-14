<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

abstract class ApiController extends Controller
{
    protected int $perPageDefault = 20;

    protected int $perPageMax = 100;

    protected int $cacheTtl = 300;

    protected function shouldCache(): bool
    {
        return app()->environment('production');
    }

    protected function cacheKey(Request $request): string
    {
        return 'api:'.str_replace('/', ':', $request->path()).':'.md5(serialize($request->query()));
    }

    protected function paginate(Builder $query, Request $request): JsonResponse
    {
        $perPage = min((int) $request->input('per_page', $this->perPageDefault), $this->perPageMax);
        $page = (int) $request->input('page', 1);

        $cacheKey = $this->cacheKey($request);

        if ($this->shouldCache() && Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $response = [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];

        if ($this->shouldCache()) {
            Cache::put($cacheKey, $response, $this->cacheTtl);
        }

        return response()->json($response);
    }

    protected function single(mixed $model, ?string $cacheKey = null): JsonResponse
    {
        if (! $model) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }

        if ($cacheKey && $this->shouldCache() && Cache::has($cacheKey)) {
            return response()->json(['data' => Cache::get($cacheKey)]);
        }

        if ($cacheKey && $this->shouldCache()) {
            Cache::put($cacheKey, $model->toArray(), $this->cacheTtl);
        }

        return response()->json(['data' => $model]);
    }
}
