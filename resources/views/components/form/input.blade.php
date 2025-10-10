@props([
    'id' => null,
    'label' => null,
    'icon' => null,
    'type' => 'text',
    'error' => null,
    'wireModel' => null, // Default to null to avoid undefined variable
])

<div class="space-y-2 mt-3">
    {{-- Label --}}
    <label for="{{ $id }}" class="block font-medium text-gray-700 flex items-center text-sm">
        @if ($icon)
            <i class="fas fa-{{ $icon }} text-green-500 mr-2 text-xs"></i>
        @endif
        {{ $label }}
    </label>

    {{-- Input with real-time validation --}}
    <input id="{{ $id }}" type="{{ $type }}"
        {{ $attributes->merge([
            'class' =>
                'w-full px-3 py-1.5
                        text-sm text-gray-800
                        border border-indigo-200
                        rounded-md
                        bg-white
                        placeholder-gray-400
                        shadow-sm
                        focus:ring-1 focus:ring-indigo-300 focus:border-indigo-300
                        hover:border-gray-400
                        transition-all duration-200 ease-in-out' . ($error ? ' border-red-400 ring-2 ring-red-100' : ''),
        ]) }}
        @if ($wireModel) wire:model.live="{{ $wireModel }}" @endif>

    {{-- Error Message --}}
    @if ($error)
        <p class="text-red-500 text-xs mt-1 flex items-center animate-fade-in">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}
        </p>
    @endif
</div>
