@props([
    'label',
    'icon' => 'star',
    'wireModel',
    'checked' => false,
    'checkedLabel' => 'Featured',
    'uncheckedLabel' => 'Not Featured',
])

<div class="space-y-2">
    <label class="block font-medium text-gray-800 flex items-center">
        <i class="fas fa-{{ $icon }} text-yellow-500 mr-2"></i>
        {{ $label }}
    </label>
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" class="sr-only peer" wire:model="{{ $wireModel }}">
        <div
            class="w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-gradient-to-r
                   peer-checked:from-blue-500 peer-checked:to-indigo-600
                   after:content-[''] after:absolute after:top-0.5 after:left-0.5
                   after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                   peer-checked:after:translate-x-6">
        </div>
        <span class="ml-3 text-sm text-gray-600" wire:target="{{ $wireModel }}" wire:loading.class="opacity-50">
            <span wire:loading.remove wire:target="{{ $wireModel }}">
                {{ $checked ? $checkedLabel : $uncheckedLabel }}
            </span>
            <span wire:loading wire:target="{{ $wireModel }}">
                <i class="fas fa-spinner fa-spin mr-1"></i>Updating...
            </span>
        </span>
    </label>
</div>
