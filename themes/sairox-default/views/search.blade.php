@extends('theme::layouts.app')

@section('title', 'Search Results - ' . get_option('site_title', 'Sairox CMS'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Search Results</h1>
        <p class="text-gray-600 mt-2">Showing results for: <strong>{{ $query }}</strong></p>
    </div>

    @if($posts->isNotEmpty())
    <div class="grid gap-6">
        @foreach($posts as $post)
            @include('theme::partials.post-card')
        @endforeach
    </div>

    @include('theme::partials.pagination', ['paginator' => $posts])
    @else
    <div class="text-center py-12">
        <p class="text-gray-500 text-lg">No results found for "{{ $query }}".</p>
        <a href="{{ url('/') }}" class="text-amber-600 hover:text-amber-700 mt-2 inline-block">Back to Home</a>
    </div>
    @endif
</div>
@endsection
