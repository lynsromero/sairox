<article class="bg-white rounded-lg shadow-sm overflow-hidden">
    @if($post->thumbnail)
    <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->post_title }}" class="w-full h-48 object-cover" loading="lazy">
    @endif
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-2">
            <a href="{{ url('/posts/' . $post->slug) }}" class="hover:text-amber-600 transition-colors">{{ $post->post_title }}</a>
        </h2>
        <p class="text-gray-600 mb-4">{{ Str::limit(strip_tags($post->post_content), 200) }}</p>
        <div class="flex items-center text-sm text-gray-500">
            <span>{{ $post->author?->name }}</span>
            <span class="mx-2">·</span>
            <span>{{ $post->created_at->format('M j, Y') }}</span>
        </div>
    </div>
</article>
