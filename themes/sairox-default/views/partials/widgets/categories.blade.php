@php
    $categories = \App\Models\Term::whereHas('taxonomy', fn($q) => $q->where('taxonomy', 'category'))
        ->withCount('posts')
        ->get();
@endphp
<div class="bg-white rounded-lg shadow-sm p-6">
    @if(!empty($settings['title']))
    <h3 class="text-lg font-semibold mb-3">{{ $settings['title'] }}</h3>
    @endif
    <ul class="space-y-2">
        @foreach($categories as $category)
        <li>
            <a href="{{ url('/categories/' . $category->slug) }}" class="text-gray-600 hover:text-amber-600 transition-colors text-sm flex justify-between">
                <span>{{ $category->name }}</span>
                <span class="text-gray-400">({{ $category->posts_count }})</span>
            </a>
        </li>
        @endforeach
    </ul>
</div>
