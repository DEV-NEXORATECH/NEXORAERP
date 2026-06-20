@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination-shell">
        <div class="pagination-mobile">
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled">Previous</span>
            @else
                <a class="pagination-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="pagination-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
            @else
                <span class="pagination-btn disabled">Next</span>
            @endif
        </div>

        <div class="pagination-desktop">
            <p class="pagination-meta">
                Showing <span>{{ $paginator->firstItem() }}</span> to <span>{{ $paginator->lastItem() }}</span> of <span>{{ $paginator->total() }}</span>
            </p>

            <div class="pagination-list">
                @if ($paginator->onFirstPage())
                    <span class="pagination-square disabled" aria-disabled="true">‹</span>
                @else
                    <a class="pagination-square" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="pagination-square disabled">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-square active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="pagination-square" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a class="pagination-square" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
                @else
                    <span class="pagination-square disabled" aria-disabled="true">›</span>
                @endif
            </div>
        </div>
    </nav>
@endif
