<div class="px-6 py-3 bg-white border-t border-gray-100/80">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <!-- Pagination Info - Compact -->
        <div
            class="flex items-center gap-2 px-3 py-1.5 bg-gradient-to-br from-slate-50 to-slate-100 rounded-lg border border-slate-200/80 shadow-sm">
            <div
                class="flex items-center justify-center w-6 h-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-md shadow-md shadow-indigo-500/30">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-800">
                <span class="text-indigo-600">{{ $paginator->firstItem() ?? 0 }}</span>
                <span class="text-slate-400 mx-0.5">-</span>
                <span class="text-indigo-600">{{ $paginator->lastItem() ?? 0 }}</span>
                <span class="text-slate-500 mx-1">of</span>
                <span class="text-slate-900">{{ number_format($paginator->total()) }}</span>
            </span>
        </div>

        <!-- Compact Pagination Controls -->
        <div class="flex items-center gap-1">
            <!-- First Page -->
            @if ($paginator->onFirstPage())
                <button disabled
                    class="flex items-center justify-center w-7 h-7 rounded-md bg-slate-100 text-slate-400 cursor-not-allowed">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            @else
                <button wire:click.prevent="gotoPage(1)"
                    class="group relative flex items-center justify-center w-7 h-7 rounded-md bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-indigo-500/20">
                    <svg class="w-3 h-3 group-hover:text-indigo-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            @endif

            <!-- Previous -->
            @if ($paginator->onFirstPage())
                <button disabled
                    class="flex items-center gap-1 px-2 py-1.5 rounded-md bg-slate-100 text-slate-400 cursor-not-allowed">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="text-xs font-semibold">Prev</span>
                </button>
            @else
                <button wire:click.prevent="previousPage"
                    class="group flex items-center gap-1 px-2 py-1.5 rounded-md bg-white border border-slate-200 text-slate-700 hover:border-indigo-300 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 hover:text-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-indigo-500/20">
                    <svg class="w-3 h-3 group-hover:translate-x-[-1px] transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="text-xs font-semibold">Prev</span>
                </button>
            @endif

            <!-- Page Numbers -->
            <div class="hidden md:flex items-center gap-1">
                @foreach ($pageRange as $page)
                    @if ($page == $paginator->currentPage())
                        <button
                            class="relative flex items-center justify-center min-w-[28px] h-7 px-2 rounded-md bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 text-white text-xs font-bold shadow-md shadow-indigo-500/40 overflow-hidden">
                            <span class="relative z-10">{{ $page }}</span>
                            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent opacity-50">
                            </div>
                        </button>
                    @else
                        <button wire:click.prevent="gotoPage({{ $page }})"
                            class="flex items-center justify-center min-w-[28px] h-7 px-2 rounded-md bg-white border border-slate-200 text-slate-700 text-xs font-semibold hover:border-indigo-300 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 hover:text-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-indigo-500/20">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            </div>

            <!-- Mobile Page Indicator -->
            <div
                class="md:hidden px-2.5 py-1.5 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-md shadow-sm">
                <span class="text-xs font-bold text-slate-800">
                    <span class="text-indigo-600">{{ $paginator->currentPage() }}</span>
                    <span class="text-slate-400 mx-0.5">/</span>
                    <span class="text-slate-600">{{ $paginator->lastPage() }}</span>
                </span>
            </div>

            <!-- Next -->
            @if ($paginator->hasMorePages())
                <button wire:click.prevent="nextPage"
                    class="group flex items-center gap-1 px-2 py-1.5 rounded-md bg-white border border-slate-200 text-slate-700 hover:border-indigo-300 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 hover:text-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-indigo-500/20">
                    <span class="text-xs font-semibold">Next</span>
                    <svg class="w-3 h-3 group-hover:translate-x-[1px] transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @else
                <button disabled
                    class="flex items-center gap-1 px-2 py-1.5 rounded-md bg-slate-100 text-slate-400 cursor-not-allowed">
                    <span class="text-xs font-semibold">Next</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @endif

            <!-- Last Page -->
            @if ($paginator->currentPage() == $paginator->lastPage())
                <button disabled
                    class="flex items-center justify-center w-7 h-7 rounded-md bg-slate-100 text-slate-400 cursor-not-allowed">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </button>
            @else
                <button wire:click.prevent="gotoPage({{ $paginator->lastPage() }})"
                    class="group relative flex items-center justify-center w-7 h-7 rounded-md bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-indigo-500/20">
                    <svg class="w-3 h-3 group-hover:text-indigo-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
</div>
