<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|in:post,page',
            'commentable_id' => 'required|integer',
            'parent_id' => 'nullable|integer|exists:comments,id',
            'author_name' => 'required_without:user_id|string|max:100',
            'author_email' => 'required_without:user_id|email|max:100',
            'content' => 'required|string|min:2|max:5000',
        ]);

        $ip = $request->ip();

        $key = 'comment:'.$ip;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['rate' => 'You are posting comments too quickly. Please try again later.']);
        }

        RateLimiter::hit($key, 600);

        $commentableType = $validated['commentable_type'] === 'post'
            ? 'App\Models\Post'
            : 'App\Models\Page';

        $depth = 0;
        if (! empty($validated['parent_id'])) {
            $parent = Comment::find($validated['parent_id']);
            $depth = $parent ? $parent->depth + 1 : 0;
            if ($depth > 3) {
                return back()->withErrors(['depth' => 'Maximum reply depth reached.']);
            }
        }

        $comment = Comment::create([
            'commentable_id' => $validated['commentable_id'],
            'commentable_type' => $commentableType,
            'user_id' => auth()->id(),
            'author_name' => $validated['author_name'] ?? auth()->user()?->name,
            'author_email' => $validated['author_email'] ?? auth()->user()?->email,
            'author_ip' => $ip,
            'content' => $validated['content'],
            'status' => 'pending',
            'parent_id' => $validated['parent_id'] ?? null,
            'depth' => $depth,
        ]);

        return back()->with('success', 'Your comment has been submitted and is awaiting moderation.');
    }
}
