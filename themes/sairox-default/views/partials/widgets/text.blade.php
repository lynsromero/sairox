<div class="bg-white rounded-lg shadow-sm p-6">
    @if(!empty($settings['title']))
    <h3 class="text-lg font-semibold mb-3">{{ $settings['title'] }}</h3>
    @endif
    <div class="text-gray-600 text-sm prose prose-sm max-w-none">
        {!! $settings['content'] ?? '' !!}
    </div>
</div>
