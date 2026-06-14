@extends('front.layout')

@section('title', 'Category: ' . $category->name)

@section('content')
    <h1 class="text-3xl font-bold mb-2">Category: {{ $category->name }}</h1>
    @if ($category->description)
        <p class="text-gray-600 mb-8">{{ $category->description }}</p>
    @endif

    @forelse ($posts as $post)
        <article class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold mb-2">
                <a href="{{ route('post', $post->slug) }}" class="text-indigo-600 hover:text-indigo-800">
                    {{ $post->post_title }}
                </a>
            </h2>
            <div class="text-sm text-gray-500 mb-3">
                {{ $post->created_at->format('M j, Y') }}
            </div>
            <p class="text-gray-700">{{ Str::limit(strip_tags($post->post_content), 300) }}</p>
        </article>
    @empty
        <p class="text-gray-500">No posts in this category.</p>
    @endforelse

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
@endsection
