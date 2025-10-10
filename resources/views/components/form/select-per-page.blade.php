<div class="relative inline-block">
    <select @if ($model) wire:model.live="{{ $model }}" @endif
        class="h-8 pl-2 pr-6 rounded-md border border-gray-300 bg-white
               text-gray-700  text-xs focus:outline-none focus:ring-1
               focus:ring-blue-500 transition-colors">
        @foreach ($options as $option)
            <option value="{{ $option }}">{{ $option }} per page</option>
        @endforeach
    </select>
</div>
