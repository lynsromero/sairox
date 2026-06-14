<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Post::with('author', 'categories', 'tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('post_title', 'like', "%{$search}%")
                    ->orWhere('post_content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $request->input('category')));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $request->input('tag')));
        }

        return $this->paginate($query->latest(), $request);
    }

    public function show(string $id): JsonResponse
    {
        $post = Post::with('author', 'categories', 'tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('id', $id)
            ->first();

        return $this->single($post, "api:posts:{$id}");
    }

    public function showBySlug(string $slug): JsonResponse
    {
        $post = Post::with('author', 'categories', 'tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('slug', $slug)
            ->first();

        return $this->single($post, "api:posts:slug:{$slug}");
    }
}
