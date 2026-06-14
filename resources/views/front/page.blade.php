@extends('front.layout')

@section('title', $page->post_title)

@section('content')
    <article class="bg-white rounded-lg shadow-sm p-8">
        <h1 class="text-3xl font-bold mb-6">{{ $page->post_title }}</h1>

        <div class="prose max-w-none">
            {!! $page->post_content !!}
        </div>
    </article>
@endsection
