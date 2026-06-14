@extends('theme::layouts.app')

@section('title', $post->post_title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <article class="bg-white rounded-lg shadow-sm p-8">
        <h1 class="text-3xl font-bold mb-6">{{ $post->post_title }}</h1>
        <div class="text-gray-500 text-sm mb-6">{{ $post->created_at->format('F j, Y') }}</div>
        <div class="prose max-w-none">{!! $post->post_content !!}</div>
    </article>
</div>
@endsection
