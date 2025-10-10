<div x-data="{
    image: null,
    fileName: null,
    fileSize: null,
    clearPreview() {
        this.image = null;
        this.fileName = null;
        this.fileSize = null;
    }
}" @upload-cleared-{{ $id }}.window="clearPreview()" class="mb-6">
    <label class="block text-gray-800 font-semibold text-sm mb-2">{{ $label ?? 'Upload Image' }}</label>

    <label
        class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-200 border-dashed rounded-xl cursor-pointer bg-white hover:bg-gray-50 transition-all duration-300 ease-in-out relative shadow-sm hover:shadow-md">

        <!-- Empty state -->
        <template x-if="!image">
            <div class="flex flex-col items-center justify-center py-6 w-full h-full">
                <svg class="w-8 h-8 mb-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 0115.9 6M9 19V9m0 0l-3 3m3-3l3 3" />
                </svg>
                <p class="text-sm text-gray-600"><span class="font-semibold text-blue-600">Click to upload</span> or
                    drag and drop</p>
                <p class="text-xs text-gray-400 mt-1">PNG, JPG, or GIF (max 5MB)</p>
            </div>
        </template>

        <!-- Preview state -->
        <template x-if="image">
            <div class="relative w-full h-full flex flex-col">
                <img :src="image"
                    class="w-full h-32 object-contain rounded-t-xl border border-gray-100 p-2" />
                <div class="bg-gray-50 p-2 rounded-b-xl border-t border-gray-100">
                    <p class="text-xs text-gray-600 truncate" x-text="fileName || 'No file selected'"></p>
                    <p class="text-xs text-gray-400" x-text="fileSize ? `${(fileSize / 1024).toFixed(2)} KB` : ''"></p>
                </div>
                <!-- Remove button -->
                <button type="button" @click.prevent="clearPreview(); $refs.fileInput.value = ''"
                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-all duration-200 z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>

        <input type="file" x-ref="fileInput" id="{{ $id }}"
            {{ $attributes->whereStartsWith('wire:model') }} accept="image/*"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            @change="
                const file = $event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => image = e.target.result;
                    reader.readAsDataURL(file);
                    fileName = file.name;
                    fileSize = file.size;
                }
            ">
    </label>

    <!-- Loading indicator -->
    <div wire:loading wire:target="categories.{{ $attributes->get('wire:model') }}" class="mt-2">
        <div class="flex items-center text-blue-600 text-sm">
            <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Uploading...
        </div>
    </div>

    @if ($error ?? false)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
