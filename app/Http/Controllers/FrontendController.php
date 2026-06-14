<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Term;

class FrontendController extends Controller
{
    public function home()
    {
        $posts = Post::with('author', 'categories', 'tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->latest()
            ->paginate(10);

        return view('front.home', compact('posts'));
    }

    public function post(string $slug)
    {
        $post = Post::with('author', 'categories', 'tags')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('front.post', compact('post'));
    }

    public function page(string $slug)
    {
        $page = Page::with('author')
            ->where('post_status', 'publish')
            ->where('slug', $slug)
            ->firstOrFail();

        $template = $page->getAttribute('template') ?? 'default';

        $view = 'front.page';

        return view($view, compact('page'));
    }

    public function category(string $slug)
    {
        $category = Term::with('taxonomy')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'category'))
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = Post::with('author')
            ->whereHas('categories', fn ($q) => $q->where('slug', $slug))
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->latest()
            ->paginate(10);

        return view('front.category', compact('category', 'posts'));
    }

    public function tag(string $slug)
    {
        $tag = Term::with('taxonomy')
            ->whereHas('taxonomy', fn ($q) => $q->where('taxonomy', 'post_tag'))
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = Post::with('author')
            ->whereHas('tags', fn ($q) => $q->where('slug', $slug))
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->latest()
            ->paginate(10);

        return view('front.tag', compact('tag', 'posts'));
    }
}
