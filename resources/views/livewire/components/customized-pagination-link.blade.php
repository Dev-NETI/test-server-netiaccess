<div class="col-lg-12 text-center">
@if ($paginator->hasPages())

        <nav aria-label="...">
            <ul class="pagination d-flex flex-wrap justify-content-center align-items-center">
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="#" tabindex="-1" wire:click="previousPage" aria-disabled="true">Previous</a>
                    </li>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1"  aria-disabled="true">{{ $element }}</a>
                        </li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <a class="page-link" href="javascript:;" tabindex="-1" wire:click="gotoPage({{ $page }})">{{ $page }}</a>
                                </li>
                            @else
                                <li class="page-item"><a href="javascript:;" wire:click="gotoPage({{ $page }})" class="page-link">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="#" tabindex="-1" wire:click="nextPage" aria-disabled="true">Next</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                    </li>
                @endif
            </ul>
        </nav>

@endif
</div>
