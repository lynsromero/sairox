@extends('front.layout')

@section('title', 'Home')

@section('content')
    <h1 class="text-3xl font-bold mb-8">Latest Posts</h1>

    @forelse ($posts as $post)
        <article class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold mb-2">
                <a href="{{ route('post', $post->slug) }}" class="text-indigo-600 hover:text-indigo-800">
                    {{ $post->post_title }}
                </a>
            </h2>
            <div class="text-sm text-gray-500 mb-3">
                By {{ $post->author?->name ?? 'Unknown' }} |
                {{ $post->created_at->format('M j, Y') }}
                @if ($post->categories->isNotEmpty())
                    | Categories:
                    @foreach ($post->categories as $cat)
                        <a href="{{ route('category', $cat->slug) }}" class="text-indigo-500">{{ $cat->name }}</a>@if (!$loop->last), @endif
                    @endforeach
                @endif
            </div>
            <p class="text-gray-700">{{ Str::limit(strip_tags($post->post_content), 300) }}</p>
            <a href="{{ route('post', $post->slug) }}" class="text-indigo-600 text-sm font-medium mt-2 inline-block">Read more</a>
        </article>
    @empty
        <p class="text-gray-500">No posts yet.</p>
    @endforelse

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
@endsection
