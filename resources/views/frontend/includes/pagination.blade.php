@if ($notes)
<div class="row">
    <div class="col-12">
        <ul class="htc-pagination clearfix">
            <!-- Previous Page Link -->
            @if ($notes->onFirstPage())
                <li class="disabled"><span>&laquo;</span></li>
            @else
                <li><a href="{{ $notes->previousPageUrl() }}">&laquo;</a></li>
            @endif

            <!-- Pagination Links -->
            @foreach ($notes->getUrlRange(1, $notes->lastPage()) as $page => $url)
                <li class="{{ $notes->currentPage() === $page ? 'active' : '' }}">
                    <a href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Next Page Link -->
            @if ($notes->hasMorePages())
                <li><a href="{{ $notes->nextPageUrl() }}">&raquo;</a></li>
            @else
                <li class="disabled"><span>&raquo;</span></li>
            @endif
        </ul>
    </div>
</div>
@endif
