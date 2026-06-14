<?php

namespace App\Http\Controllers\Api;

use App\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Term::withCount('posts')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'post_tag'));

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        return $this->paginate($query->orderBy('name'), $request);
    }

    public function show(string $id): JsonResponse
    {
        $tag = Term::withCount('posts')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'post_tag'))
            ->where('id', $id)
            ->first();

        return $this->single($tag, "api:tags:{$id}");
    }

    public function showBySlug(string $slug): JsonResponse
    {
        $tag = Term::withCount('posts')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'post_tag'))
            ->where('slug', $slug)
            ->first();

        return $this->single($tag, "api:tags:slug:{$slug}");
    }
}
