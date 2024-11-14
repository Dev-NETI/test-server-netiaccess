<div class="col-lg-12 text-center">
    @if ($paginator->hasPages())
    <nav aria-label="...">
        <ul class="pagination d-flex flex-wrap justify-content-center align-items-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="javascript:;" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="javascript:;" wire:click="previousPage('{{$pagename}}')"
                    aria-disabled="true">Previous</a>
            </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
            <li class="page-item disabled">
                <a class="page-link" href="javascript:;" tabindex="-1" aria-disabled="true">{{ $element }}</a>
            </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <li class="page-item active" aria-current="page">
                <a class="page-link" href="javascript:;" wire:click="gotoPage({{ $page }}, '{{$pagename}}')">{{ $page
                    }}</a>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="javascript:;" wire:click="gotoPage({{ $page }}, '{{$pagename}}')">{{ $page
                    }}</a>
            </li>
            @endif
            @endforeach
            @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="javascript:;" wire:click="nextPage('{{$pagename}}')"
                    aria-disabled="true">Next</a>
            </li>
            @else
            <li class="page-item disabled">
                <a class="page-link" href="javascript:;" tabindex="-1" aria-disabled="true">Next</a>
            </li>
            @endif
        </ul>
    </nav>
    @endif
</div>