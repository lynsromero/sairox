<?php

namespace App\Http\Controllers\Api;

use App\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Term::withCount('posts')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'category'))
            ->whereNull('parent_id');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        return $this->paginate($query->orderBy('name'), $request);
    }

    public function show(string $id): JsonResponse
    {
        $category = Term::withCount('posts')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'category'))
            ->where('id', $id)
            ->first();

        return $this->single($category, "api:categories:{$id}");
    }

    public function showBySlug(string $slug): JsonResponse
    {
        $category = Term::withCount('posts')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'category'))
            ->where('slug', $slug)
            ->first();

        return $this->single($category, "api:categories:slug:{$slug}");
    }
}
