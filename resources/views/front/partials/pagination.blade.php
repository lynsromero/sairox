@if ($paginator->hasPages())
<div class="flex justify-center gap-2 mt-8">
    @if ($paginator->onFirstPage())
    <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">Previous</span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:bg-amber-50 hover:text-amber-600 transition-colors">Previous</a>
    @endif
    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
        @if ($page == $paginator->currentPage())
        <span class="px-4 py-2 bg-amber-600 text-white rounded-lg">{{ $page }}</span>
        @else
        <a href="{{ $url }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:bg-amber-50 hover:text-amber-600 transition-colors">{{ $page }}</a>
        @endif
    @endforeach
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:bg-amber-50 hover:text-amber-600 transition-colors">Next</a>
    @else
    <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">Next</span>
    @endif
</div>
@endif
