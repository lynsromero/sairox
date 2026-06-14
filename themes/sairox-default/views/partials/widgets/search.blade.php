<div class="bg-white rounded-lg shadow-sm p-6">
    @if(!empty($settings['title']))
    <h3 class="text-lg font-semibold mb-3">{{ $settings['title'] }}</h3>
    @endif
    <form action="{{ url('/search') }}" method="GET" class="flex gap-2">
        <input type="text" name="q" placeholder="Search..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
        <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700 transition-colors">Search</button>
    </form>
</div>
