@extends('theme::layouts.app')

@section('title', $page->post_title . ' - ' . get_option('site_title', 'Sairox CMS'))

@section('meta_description', Str::limit(strip_tags($page->post_excerpt ?? $page->post_content), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <article>
        @if($page->thumbnail)
        <img src="{{ Storage::url($page->thumbnail) }}" alt="{{ $page->post_title }}" class="w-full h-64 md:h-96 object-cover rounded-lg shadow-sm mb-8" loading="lazy">
        @endif

        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">{{ $page->post_title }}</h1>

        <div class="prose prose-lg max-w-none">
            {!! $page->post_content !!}
        </div>
    </article>
</div>
@endsection
