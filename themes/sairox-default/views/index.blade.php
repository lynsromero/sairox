@extends('theme::layouts.app')

@section('title', get_option('site_title', 'Sairox CMS') . ' - ' . get_option('tagline', ''))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ get_option('site_title', 'Sairox CMS') }}</h1>
        @if(get_option('tagline'))
        <p class="text-gray-600 mt-2">{{ get_option('tagline') }}</p>
        @endif
    </div>

    <div class="grid gap-6">
        @forelse($posts as $post)
            @include('theme::partials.post-card')
        @empty
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">No posts published yet.</p>
        </div>
        @endforelse
    </div>

    @include('theme::partials.pagination', ['paginator' => $posts])
</div>
@endsection
