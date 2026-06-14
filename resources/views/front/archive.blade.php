@extends('theme::layouts.app')

@section('title', $title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ $title }}</h1>
    <div class="grid gap-6">
        @forelse($posts as $post)
        <article class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-2">
                <a href="{{ url('/posts/' . $post->slug) }}" class="hover:text-amber-600 transition-colors">{{ $post->post_title }}</a>
            </h2>
            <p class="text-gray-600">{{ Str::limit(strip_tags($post->post_content), 200) }}</p>
        </article>
        @empty
        <p class="text-gray-500 text-center py-12">No posts found.</p>
        @endforelse
    </div>
</div>
@endsection
