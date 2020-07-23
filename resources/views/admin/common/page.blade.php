<div class="layui-card-body ">
    <div class="page">
        @if ($paginator->hasPages())
            <div>
                @if ($paginator->onFirstPage())
                    <span class="prev layui-disabled">&lt;&lt;</span>
                @else
                    <a class="prev" href="{{ $paginator->previousPageUrl() }}">&lt;&lt;</a>
                @endif

                @foreach ($elements as $element)

                    @if (is_string($element))
                        <span class="">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="current">{{ $page }}</span>
                            @else
                                <a class="num" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif

                @endforeach

                @if ($paginator->hasMorePages())
                    <a class="next" href="{{ $paginator->nextPageUrl() }}">&gt;&gt;</a>
                @else
                    <span class="next layui-disabled">&gt;&gt;</span>
                @endif
            </div>
        @endif
    </div>
</div>


