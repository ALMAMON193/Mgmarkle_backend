@props([
    'colspan' => 8,
    'title' => 'No Item Found',
    'message' => 'It looks like there are no items available at the moment. Try adding a new one!',
    'icon' => null,
])

<tr>
    <td colspan="{{ $colspan }}"
        class="px-4 lg:px-6 py-8 lg:py-12 text-center bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="flex flex-col items-center justify-center space-y-4 animate-fade-in">
            @if ($icon)
                <!-- Render raw HTML safely -->
                {!! $icon !!}
            @else
                <!-- Default Icon -->
                <svg class="w-12 h-12 text-indigo-500 transition-transform duration-300 hover:scale-110"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7m-9 4h6" />
                </svg>
            @endif

            <p class="text-lg font-semibold text-gray-600 tracking-wide">{{ $title }}</p>
            <p class="text-sm text-gray-500 max-w-sm">{{ $message }}</p>
        </div>
    </td>
</tr>
