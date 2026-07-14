@if($paginator->hasPages())
    <nav class="pagination" aria-label="Навигация по страницам">
        @if($paginator->onFirstPage())
            <span class="pagination-arrow is-disabled" aria-disabled="true">Назад</span>
        @else
            <a class="pagination-arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev">Назад</a>
        @endif

        <div class="pagination-pages">
            @foreach($elements as $element)
                @if(is_string($element))
                    <span class="pagination-gap" aria-hidden="true">{{ $element }}</span>
                @else
                    @foreach($element as $page => $url)
                        @if($page === $paginator->currentPage())
                            <span class="pagination-page is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-page" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if($paginator->hasMorePages())
            <a class="pagination-arrow" href="{{ $paginator->nextPageUrl() }}" rel="next">Вперёд</a>
        @else
            <span class="pagination-arrow is-disabled" aria-disabled="true">Вперёд</span>
        @endif
    </nav>
@endif
