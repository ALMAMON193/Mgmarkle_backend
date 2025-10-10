@props(['id', 'label', 'icon' => null, 'error' => null, 'wireModel'])

<div class="space-y-2">
    <label for="{{ $id }}" class="block font-medium text-gray-800 flex items-center">
        @if ($icon)
            <i class="fas fa-{{ $icon }} text-green-500 mr-2"></i>
        @endif
        {{ $label }}
    </label>
    <textarea id="{{ $id }}" rows="3"
        {{ $attributes->merge([
            'class' =>
                'w-full px-4 py-3 border border-indigo-200 rounded-md focus:ring-1 focus:ring-green-500
                        focus:border-transparent transition-all duration-300 bg-white hover:border-indigo-300
                        placeholder-gray-400 resize-none ' . ($error ? 'border-red-400 ring-1 ring-red-100' : ''),
        ]) }}
        wire:model.live.debounce.500ms="{{ $wireModel }}"></textarea>
    @if ($error)
        <p class="text-red-500 text-sm mt-1 flex items-center animate-pulse">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}
        </p>
    @endif
</div>
