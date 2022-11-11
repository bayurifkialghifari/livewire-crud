<div>
    @if ($paginator->hasPages())
        <nav aria-label="page navigation" role="navigation">
            <ul class="pagination">
                @if ($paginator->onFirstPage())
                    <li class="page-item"><span class="page-link">{!! __('pagination.previous') !!}</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="#" wire:click="previousPage" wire:loading.attr="disabled" rel="prev">{!! __('pagination.previous') !!}</a>
                    </li>
                @endif

                @foreach ($elements as $element)
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" wire:click="gotoPage({{$page}})"><a class="page-link" href="#">{{$page}}</a></li>
                            @else
                                <li class="page-item" wire:click="gotoPage({{$page}})"><a class="page-link" href="#">{{$page}}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="#" wire:click="nextPage" wire:loading.attr="disabled" rel="next">{!! __('pagination.next') !!}</a>
                    </li>
                @else
                    <li class="page-item"><a class="page-link">  {!! __('pagination.next') !!}</a></li>
                @endif
            </ul>
        </nav>
    @endif
</div>
