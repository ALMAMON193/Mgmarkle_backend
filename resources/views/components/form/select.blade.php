@props(['id', 'label', 'icon' => null, 'error' => null, 'wireModel', 'options' => []])

<div class="space-y-1">
    {{-- Label --}}
    <label for="{{ $id }}" class="block font-medium text-gray-800 flex items-center">
        @if ($icon)
            <i class="fas fa-{{ $icon }} text-blue-500 mr-2"></i>
        @endif
        {{ $label }}
    </label>

    <div class="relative">
        <select id="{{ $id }}"
            {{ $attributes->merge([
                'class' =>
                    '
                                w-full px-3 py-1.5
                                text-sm text-gray-800
                                border border-indigo-200
                                rounded-md
                                bg-white
                                placeholder-gray-400
                                shadow-sm
                                focus:ring-1 focus:ring-indigo-300 focus:border-indigo-300
                                hover:border-gray-400
                                transition-all duration-200 ease-in-out
                                ' . ($error ? ' border-red-400 ring-2 ring-red-100' : ''),
            ]) }}
            wire:model.live="{{ $wireModel }}">
            {{-- Placeholder --}}
            <option value="">{{ $label ? 'Select ' . $label : 'Select' }}</option>

            {{-- Options --}}
            @foreach ($options as $value => $optionLabel)
                <option value="{{ $value }}">{{ $optionLabel }}</option>
            @endforeach
        </select>
    </div>

    {{-- Error message --}}
    @if ($error)
        <p class="text-red-500 text-sm mt-1 flex items-center animate-pulse">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}
        </p>
    @endif
</div>
