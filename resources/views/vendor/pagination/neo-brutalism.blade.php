@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4 w-full text-xs font-bold text-[#1A1A2E]">
        <!-- Info text -->
        <div class="text-slate-600">
            Menampilkan <span class="font-extrabold text-[#1A1A2E]">{{ $paginator->firstItem() }}</span> sampai <span class="font-extrabold text-[#1A1A2E]">{{ $paginator->lastItem() }}</span> dari <span class="font-extrabold text-[#1A1A2E]">{{ $paginator->total() }}</span> data
        </div>

        <!-- Page Buttons -->
        <div class="flex items-center gap-1.5 flex-wrap">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 bg-slate-100 border-2 border-slate-300 text-slate-400 rounded-lg font-bold cursor-not-allowed">
                    ‹ Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 bg-[#FFFBEB] hover:bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-extrabold transition-all">
                    ‹ Prev
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-2 py-1.5 text-slate-400 font-bold">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3.5 py-1.5 bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-extrabold text-[#1A1A2E]">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-3.5 py-1.5 bg-white hover:bg-[#FFFBEB] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-bold text-[#1A1A2E] transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 bg-[#FFFBEB] hover:bg-[#FFE156] border-2 border-[#1A1A2E] shadow-[2px_2px_0px_#1A1A2E] rounded-lg font-extrabold transition-all">
                    Next ›
                </a>
            @else
                <span class="px-3 py-1.5 bg-slate-100 border-2 border-slate-300 text-slate-400 rounded-lg font-bold cursor-not-allowed">
                    Next ›
                </span>
            @endif
        </div>
    </nav>
@endif
