@extends('front.layout')

@section('title', $post->post_title)

@section('content')
    <article class="bg-white rounded-lg shadow-sm p-8">
        <h1 class="text-3xl font-bold mb-4">{{ $post->post_title }}</h1>

        <div class="text-sm text-gray-500 mb-6">
            By {{ $post->author?->name ?? 'Unknown' }} |
            {{ $post->created_at->format('F j, Y') }}
            @if ($post->categories->isNotEmpty())
                | Categories:
                @foreach ($post->categories as $cat)
                    <a href="{{ route('category', $cat->slug) }}" class="text-indigo-500">{{ $cat->name }}</a>@if (!$loop->last), @endif
                @endforeach
            @endif
        </div>

        <div class="prose max-w-none">
            {!! $post->post_content !!}
        </div>

        @if ($post->tags->isNotEmpty())
            <div class="mt-8 pt-6 border-t">
                <span class="text-sm font-medium text-gray-600">Tags:</span>
                @foreach ($post->tags as $tag)
                    <a href="{{ route('tag', $tag->slug) }}" class="inline-block bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full ml-2 hover:bg-indigo-100 hover:text-indigo-700">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </article>
@endsection
