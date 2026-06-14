<aside class="space-y-6">
    @php $sidebarWidgets = \App\Models\Widget::whereHas('area', fn($q) => $q->where('slug', 'sidebar'))->orderBy('order')->get(); @endphp
    @foreach($sidebarWidgets as $widget)
        <div class="widget widget-{{ $widget->type }}">
            @includeIf("theme::partials.widgets.{$widget->type}", ['settings' => $widget->settings])
        </div>
    @endforeach
</aside>
