<footer class="bg-white border-t mt-12">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ get_option('site_title', 'Sairox CMS') }}. All rights reserved.
            </p>
            <nav class="flex gap-4 text-sm">
                @php $footerMenu = \App\Models\Menu::where('location', 'footer')->with('items.children')->first(); @endphp
                @if($footerMenu)
                    @foreach($footerMenu->items->where('parent_id', null) as $item)
                    <a href="{{ $item->resolved_url }}" target="{{ $item->target }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                        {{ $item->title }}
                    </a>
                    @endforeach
                @endif
            </nav>
        </div>
    </div>
</footer>
