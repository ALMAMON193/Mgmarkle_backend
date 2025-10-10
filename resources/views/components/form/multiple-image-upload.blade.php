<div x-data="{ images: [] }" class="mb-6">
    <label class="block text-gray-700 font-medium mb-2 flex items-center">
        {{ $label ?? 'Upload Images' }} (Max {{ $max ?? 20 }})
        <i class="fas fa-info-circle ml-2 text-gray-400 hover:text-gray-600 cursor-help"
            title="Upload up to {{ $max ?? 20 }} images"></i>
    </label>

    <label
        class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors relative">
        <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="images.length === 0">
            <svg class="w-6 h-6 mb-2 text-gray-500" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
            </svg>
            <p class="mb-1 text-xs text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop
            </p>
            <p class="text-[10px] text-gray-400">Any image format (max 5MB each)</p>
        </div>

        <input type="file" name="{{ $name ?? 'images[]' }}" multiple accept="image/*"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            @change="
                Array.from($event.target.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => images.push(e.target.result);
                    reader.readAsDataURL(file);
                })
            ">
    </label>

    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <template x-for="(img, index) in images" :key="index">
            <div class="relative group w-24 h-24">
                <img :src="img" class="w-full h-full object-cover rounded-lg border border-gray-200" />
                <button type="button" @click="images.splice(index, 1)"
                    class="absolute -top-2 -left-2 opacity-0 group-hover:opacity-100 transition-opacity bg-white hover:bg-gray-100 text-gray-700 rounded-full p-1.5 border border-gray-300 shadow-sm">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
