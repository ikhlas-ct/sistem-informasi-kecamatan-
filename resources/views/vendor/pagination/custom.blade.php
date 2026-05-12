@if ($paginator->hasPages())
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="page-link" aria-hidden="true">«</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">«</a>
            </li>
        @endif

        {{-- Halaman 1 sampai 4 --}}
        @for ($page = 1; $page <= min(4, $paginator->lastPage()); $page++)
            @if ($page == $paginator->currentPage())
                <li class="page-item active" aria-current="page">
                    <span class="page-link">{{ $page }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                </li>
            @endif
        @endfor

        {{-- Jika total halaman lebih dari 4, tampilkan ellipsis dan link ke halaman terakhir --}}
        @if ($paginator->lastPage() > 4)
            {{-- Ellipsis --}}
            <li class="page-item disabled"><span class="page-link">...</span></li>

            {{-- Halaman Terakhir --}}
            @php
                $lastPage = $paginator->lastPage();
            @endphp

            @if ($paginator->currentPage() == $lastPage)
                <li class="page-item active" aria-current="page">
                    <span class="page-link">{{ $lastPage }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                </li>
            @endif
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">»</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="page-link" aria-hidden="true">»</span>
            </li>
        @endif
@endif
