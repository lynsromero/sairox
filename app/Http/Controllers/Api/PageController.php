<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Page::with('author')
            ->where('post_status', 'publish');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('post_title', 'like', "%{$search}%")
                    ->orWhere('post_content', 'like', "%{$search}%");
            });
        }

        return $this->paginate($query->latest(), $request);
    }

    public function show(string $id): JsonResponse
    {
        $page = Page::with('author')
            ->where('post_status', 'publish')
            ->where('id', $id)
            ->first();

        return $this->single($page, "api:pages:{$id}");
    }

    public function showBySlug(string $slug): JsonResponse
    {
        $page = Page::with('author')
            ->where('post_status', 'publish')
            ->where('slug', $slug)
            ->first();

        return $this->single($page, "api:pages:slug:{$slug}");
    }
}
