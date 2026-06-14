@php
    $count = $settings['count'] ?? 5;
    $recentPosts = \App\Models\Post::where('post_type', 'post')
        ->where('post_status', 'publish')
        ->latest()
        ->take($count)
        ->get();
@endphp
<div class="bg-white rounded-lg shadow-sm p-6">
    @if(!empty($settings['title']))
    <h3 class="text-lg font-semibold mb-3">{{ $settings['title'] }}</h3>
    @endif
    <ul class="space-y-2">
        @foreach($recentPosts as $post)
        <li>
            <a href="{{ url('/posts/' . $post->slug) }}" class="text-gray-600 hover:text-amber-600 transition-colors text-sm">
                {{ $post->post_title }}
            </a>
        </li>
        @endforeach
    </ul>
</div>
