{{-- Logo Header --}}
<div class="sticky top-0 z-10 bg-white shadow-sm border-gray-100 px-5 py-4">
    <div class="flex items-center gap-3">
        <div
            class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-sm">
            <i class="fas fa-graduation-cap text-white text-base"></i>
        </div>
        <div>
            <h1 class="text-base font-bold text-gray-900">LMS Platform</h1>
            <p class="text-xs text-gray-500">Admin Panel</p>
        </div>
    </div>
</div>

{{-- Navigation --}}
<div class="flex-1 border-r border-gray-100 overflow-y-auto px-3 py-4">
    <nav class="space-y-1">
        {{-- Dashboard --}}
        <flux:button href="#" variant="ghost" class="w-full !justify-start !text-gray-700 hover:!bg-gray-50">
            <flux:icon.home class="size-5" />
            <span class="ml-3">Dashboard</span>
        </flux:button>

        {{-- Categories --}}
        <flux:button href="{{ route('category.index') }}" variant="ghost"
            class="w-full !justify-start !text-gray-700 hover:!bg-gray-50">
            <flux:icon.list-bullet class="size-5" />
            <span class="ml-3">Categories</span>
        </flux:button>

        {{-- Courses --}}
        <div x-data="{ open: false }">
            <flux:button @click="open = !open" variant="ghost"
                class="w-full !justify-between !text-gray-700 hover:!bg-gray-50">
                <div class="flex items-center">
                    <flux:icon.academic-cap class="size-5" />
                    <span class="ml-3">Courses</span>
                </div>
                <flux:icon.chevron-down class="size-4" x-bind:class="open && 'rotate-180'" />
            </flux:button>
            <div x-show="open" x-cloak x-transition class="ml-8 mt-1 space-y-1 pl-4 border-l-2 border-gray-100">
                <flux:button href="#" variant="ghost"
                    class="w-full !justify-start !text-sm !text-gray-600 hover:!text-gray-900 hover:!bg-gray-50">
                    All Courses
                </flux:button>
                <flux:button href="#" variant="ghost"
                    class="w-full !justify-start !text-sm !text-gray-600 hover:!text-gray-900 hover:!bg-gray-50">
                    Add New Course
                </flux:button>
                <flux:button href="#" variant="ghost"
                    class="w-full !justify-start !text-sm !text-gray-600 hover:!text-gray-900 hover:!bg-gray-50">
                    Manage Courses
                </flux:button>
            </div>
        </div>

        {{-- Enrollments --}}
        <div x-data="{ open: false }">
            <flux:button @click="open = !open" variant="ghost"
                class="w-full !justify-between !text-gray-700 hover:!bg-gray-50">
                <div class="flex items-center">
                    <flux:icon.user-group class="size-5" />
                    <span class="ml-3">Enrollments</span>
                </div>
                <flux:icon.chevron-down class="size-4" x-bind:class="open && 'rotate-180'" />
            </flux:button>
            <div x-show="open" x-cloak x-transition class="ml-8 mt-1 space-y-1 pl-4 border-l-2 border-gray-100">
                <flux:button href="#" variant="ghost"
                    class="w-full !justify-start !text-sm !text-gray-600 hover:!text-gray-900 hover:!bg-gray-50">
                    All Enrollments
                </flux:button>
                <flux:button href="#" variant="ghost"
                    class="w-full !justify-start !text-sm !text-gray-600 hover:!text-gray-900 hover:!bg-gray-50">
                    Pending Requests
                </flux:button>
            </div>
        </div>

        {{-- Instructors --}}
        <div x-data="{ open: false }">
            <flux:button @click="open = !open" variant="ghost"
                class="w-full !justify-between !text-gray-700 hover:!bg-gray-50">
                <div class="flex items-center">
                    <flux:icon.users class="size-5" />
                    <span class="ml-3">Instructors</span>
                </div>
                <flux:icon.chevron-down class="size-4" x-bind:class="open && 'rotate-180'" />
            </flux:button>
            <div x-show="open" x-cloak x-transition class="ml-8 mt-1 space-y-1 pl-4 border-l-2 border-gray-100">
                <flux:button href="#" variant="ghost"
                    class="w-full !justify-start !text-sm !text-gray-600 hover:!text-gray-900 hover:!bg-gray-50">
                    All Instructors
                </flux:button>
                <flux:button href="#" variant="ghost"
                    class="w-full !justify-start !text-sm !text-gray-600 hover:!text-gray-900 hover:!bg-gray-50">
                    Add Instructor
                </flux:button>
            </div>
        </div>

        {{-- Settings --}}
        <div x-data="{ open: false }">
            <flux:button @click="open = !open" variant="ghost"
                class="w-full !justify-between !text-gray-700 hover:!bg-gray-50">
                <div class="flex items-center">
                    <flux:icon.cog-6-tooth class="size-5" />
                    <span class="ml-3">Settings</span>
                </div>
                <flux:icon.chevron-down class="size-4" x-bind:class="open && 'rotate-180'" />
            </flux:button>
            <div x-show="open" x-cloak x-transition class="ml-8 mt-1 space-y-1 pl-4 border-l-2 border-gray-100">
                <flux:button href="#" variant="ghost"
                    class="w-full !justify-start !text-sm !text-gray-600 hover:!text-gray-900 hover:!bg-gray-50">
                    General
                </flux:button>
                <flux:button href="#" variant="ghost"
                    class="w-full !justify-start !text-sm !text-gray-600 hover:!text-gray-900 hover:!bg-gray-50">
                    Notifications
                </flux:button>
            </div>
        </div>
    </nav>
</div>

{{-- User Profile Footer --}}
<div class="mt-auto border-t border-gray-100 p-3">
    <flux:button variant="ghost" class="w-full !justify-start !px-3">
        <div
            class="w-9 h-9 rounded-lg bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
            AU
        </div>
        <div class="flex-1 text-left ml-3 min-w-0">
            <div class="text-sm font-semibold text-gray-900 truncate">Admin User</div>
            <div class="text-xs text-gray-500 truncate">admin@example.com</div>
        </div>
        <flux:icon.chevron-up-down variant="micro" class="text-gray-400 ml-2" />
    </flux:button>
</div>
