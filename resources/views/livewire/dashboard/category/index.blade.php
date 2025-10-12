<main class="flex-1 overflow-y-auto p-4 no-scrollbar pt-6 ps-8">
    <div class="space-y-4">
        <!-- Page Header -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Badge Categories</h1>
            <p class="text-xs text-gray-600 mt-0.5">Manage and organize your badge categories</p>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Card Header -->
            <div class="p-3 border-b border-gray-200">
                <!-- Search and Actions Bar -->
                <div class="flex flex-col md:flex-row gap-2 mb-2">
                    <div class="relative flex-1 max-w-xs">
                        <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400"
                            style="font-size: 10px;"></i>
                        <input type="search" wire:model.live.debounce.300ms="search"
                            class="w-full h-8 pl-8 pr-2 text-xs rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Search..." />
                    </div>
                    <div class="flex gap-1.5">
                        <!-- Per Page Dropdown -->
                        <div class="relative">
                            <select wire:model.live="perPage"
                                class="h-8 pl-2 pr-6 rounded-md border border-gray-300 bg-white text-gray-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="5">5 per page</option>
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                                <option value="100">100 per page</option>
                            </select>
                        </div>

                        <button wire:click="$toggle('showFilters')"
                            class="h-8 px-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-xs font-medium transition-colors flex items-center gap-1">
                            <i class="fas fa-filter" style="font-size: 10px;"></i>
                            <span>Filters</span>
                            @if ($filterStatus !== '' || $filterDate !== '')
                                <span class="px-1 py-0.5 text-xs bg-blue-600 text-white rounded-full"
                                    style="font-size: 9px;">
                                    {{ collect([$filterStatus, $filterDate])->filter()->count() }}
                                </span>
                            @endif
                        </button>
                        <button wire:click="openModal"
                            class="h-8 px-2.5 bg-blue-600 text-white rounded-md text-xs font-medium transition-colors flex items-center gap-1">
                            <i class="fas fa-plus" style="font-size: 9px;"></i>
                            Add New
                        </button>
                    </div>
                </div>

                <!-- Filter Panel -->
                @if ($showFilters)
                    <div class="bg-gray-50 rounded-md p-2.5 border border-gray-200 mt-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <!-- Status Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                <select wire:model.live="filterStatus"
                                    class="w-full h-8 pl-2 pr-6 rounded-md border border-gray-300 bg-white text-gray-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Created Date</label>
                                <input type="date" wire:model.live="filterDate"
                                    class="w-full h-8 px-2 text-xs rounded-md border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sort By</label>
                                <select wire:model.live="sortBy"
                                    class="w-full h-8 pl-2 pr-6 rounded-md border border-gray-300 bg-white text-gray-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="latest">Latest</option>
                                    <option value="oldest">Oldest</option>
                                    <option value="name_asc">Name (A-Z)</option>
                                    <option value="name_desc">Name (Z-A)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-2">
                            <button wire:click="clearFilters"
                                class="px-2 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded transition-all hover:scale-105 active:scale-95">
                                Clear
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Table -->
            <div class="overflow-x-auto relative no-scrollbar">
                <table class="w-full">
                    <thead class="bg-gray-50/80 backdrop-blur-sm border-b border-gray-200">
                        <tr class="text-[12px] font-semibold text-gray-600 tracking-wide uppercase">
                            <th class="px-5 py-3 text-left">#</th>
                            <th class="px-5 py-3 text-left">Name</th>
                            <th class="px-5 py-3 text-left">Description</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($categories as $index => $category)
                            <tr class="hover:bg-gray-50 transition-colors" wire:key="category-{{ $category->id }}">
                                <td class="px-3 py-2 text-xs text-gray-900 animate-fadeIn"
                                    style="animation-delay: {{ $index * 50 }}ms;">
                                    {{ $category->id }}
                                </td>
                                <td class="px-3 py-2 text-xs text-gray-900 animate-fadeIn"
                                    style="animation-delay: {{ $index * 50 + 100 }}ms;">
                                    {{ $category->name }}
                                </td>
                                <td class="px-3 py-2 text-xs text-gray-600 animate-fadeIn"
                                    style="animation-delay: {{ $index * 50 + 200 }}ms;">
                                    {{ $category->description ?? '-' }}
                                </td>
                                <td class="px-3 py-2 animate-fadeIn"
                                    style="animation-delay: {{ $index * 50 + 300 }}ms;">
                                    @if ($category->status === 'active')
                                        <span
                                            class="inline-flex items-center gap-1 px-1.5 py-0.5 text-xs font-medium rounded bg-green-50 text-green-700 border border-green-200">
                                            <span class="w-1 h-1 rounded-full bg-green-500 animate-pulse"></span>
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-1.5 py-0.5 text-xs font-medium rounded bg-gray-50 text-gray-600 border border-gray-200">
                                            <span class="w-1 h-1 rounded-full bg-gray-400"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-right animate-fadeIn"
                                    style="animation-delay: {{ $index * 50 + 350 }}ms;">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button wire:click="edit({{ $category->id }})" wire:loading.attr="disabled"
                                            class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded transition-all hover:scale-105 active:scale-95">
                                            <i class="fas fa-edit mr-1" style="font-size: 9px;"></i>Edit
                                        </button>
                                        <button wire:click="delete({{ $category->id }})" wire:loading.attr="disabled"
                                            class="px-2 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded transition-all hover:scale-105 active:scale-95"
                                            onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="fas fa-trash mr-1" style="font-size: 9px;"></i>Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <i class="fas fa-inbox text-2xl text-gray-300"></i>
                                        <p class="text-xs font-medium text-gray-900">No categories found</p>
                                        <p class="text-xs text-gray-500" style="font-size: 10px;">Try adjusting your
                                            search or filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <x-form.pagination :paginator="$categories" :pageRange="$pageRange" />
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50"
            x-data="{ open: true }" x-show="open" @keydown.escape.window="open = false; $wire.closeModal()">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ $categoryId ? 'Edit Category' : 'Add New Category' }}
                </h2>
                <form wire:submit.prevent="{{ $categoryId ? 'update' : 'store' }}">
                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" wire:model.live="name"
                            class="w-full h-8 px-2 text-xs rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Enter name" />
                        @error('name')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model.live="description"
                            class="w-full h-20 px-2 py-1 text-xs rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Enter description"></textarea>
                        @error('description')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model.live="status"
                            class="w-full h-8 pl-2 pr-6 rounded-md border border-gray-300 bg-white text-gray-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeModal"
                            class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded transition-all">
                            {{ $categoryId ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- CSS Animations -->
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</main>
