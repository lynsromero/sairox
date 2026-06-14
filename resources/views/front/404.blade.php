@extends('theme::layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16 text-center">
    <h1 class="text-6xl font-bold text-gray-200 mb-4">404</h1>
    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Page Not Found</h2>
    <p class="text-gray-600 mb-8">The page you are looking for does not exist or has been moved.</p>
    <a href="{{ url('/') }}" class="inline-block px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">Back to Home</a>
</div>
@endsection
