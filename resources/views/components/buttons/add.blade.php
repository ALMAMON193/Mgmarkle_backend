@props(['icon' => 'plus', 'wireClick' => null, 'href' => null])

@if ($href)
    <a href="{{ $href }}"
        class="h-8 px-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700
           text-white rounded-md text-xs font-medium transition-all duration-300
           flex items-center gap-1 shadow-sm hover:shadow-md transform hover:scale-105">
        <i class="fas fa-{{ $icon }} mr-2"></i> {{ $slot }}
    </a>
@else
    <button type="button" @if ($wireClick) wire:click="{{ $wireClick }}" @endif
        class="h-8 px-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700
           text-white rounded-md text-xs font-medium transition-all duration-300
           flex items-center gap-1 shadow-sm hover:shadow-md transform hover:scale-105">
        <i class="fas fa-{{ $icon }} mr-2"></i> {{ $slot }}
    </button>
@endif
