<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="text-xl font-bold text-amber-600 hover:text-amber-700 transition-colors">
            {{ get_option('site_title', 'Sairox CMS') }}
        </a>
        <nav class="flex gap-6">
            @php $primaryMenu = \App\Models\Menu::where('location', 'primary')->with('items.children')->first(); @endphp
            @if($primaryMenu)
                @foreach($primaryMenu->items->where('parent_id', null) as $item)
                <a href="{{ $item->resolved_url }}" target="{{ $item->target }}" class="text-gray-600 hover:text-amber-600 transition-colors">
                    {{ $item->title }}
                </a>
                @endforeach
            @else
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-amber-600 transition-colors">Home</a>
            @endif
        </nav>
    </div>
</header>
