<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Professional Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- ApexCharts for Data Visualization -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @livewireStyles
</head>

<body>
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"></div>

        <!-- Mobile Menu Button -->
        <button id="mobileSidebarButton" class="lg:hidden p-2 text-gray-800 bg-white rounded-md fixed top-3 left-4 z-10">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <!-- Sidebar -->
        <aside id="sidebar"
            class="bg-[hsl(222,47%,11%)] text-[hsl(210,40%,98%)] shadow-xl transition-all duration-300 fixed lg:static -translate-x-full lg:translate-x-0 z-50 w-64 h-screen"
            data-collapsed="false">
            <!-- Sidebar Header -->
            <div class="flex h-16 items-center justify-center border-b border-[hsl(222,47%,15%)]">
                <div class="flex items-center gap-3">
                    <svg id="sidebarIcon" class="hidden h-6 w-6 text-[hsl(217,91%,60%)]"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                        <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                        <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                        <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                    </svg>
                    <h1 id="sidebarTitle" class="text-xl font-bold text-[hsl(217,91%,60%)]">
                        AdminPro
                    </h1>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto p-4 no-scrollbar">
                <ul class="space-y-2">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="sidebar_links flex items-center gap-3 rounded-lg px-3 py-2 transition-colors hover:bg-[hsl(222,47%,20%)] hover:text-[hsl(217,91%,60%)] font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-layout-dashboard h-5 w-5 flex-shrink-0">
                                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                            </svg>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <!-- category -->
                    <li>
                        <a href="{{ route('category.index') }}"
                            class="sidebar_links flex items-center gap-3 rounded-lg px-3 py-2 transition-colors hover:bg-[hsl(222,47%,20%)] hover:text-[hsl(217,91%,60%)] font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-layout-dashboard h-5 w-5 flex-shrink-0">
                                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                            </svg>
                            <span class="sidebar-text">Category</span>
                        </a>
                    </li>
                    <!-- Sub  category -->
                    <li>
                        <a href="{{ route('sub-category.index') }}"
                            class="sidebar_links flex items-center gap-3 rounded-lg px-3 py-2 transition-colors hover:bg-[hsl(222,47%,20%)] hover:text-[hsl(217,91%,60%)] font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-layout-dashboard h-5 w-5 flex-shrink-0">
                                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                            </svg>
                            <span class="sidebar-text">Sub Category</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex h-16 items-center justify-between border-b bg-white px-6 shadow-sm">
                <div class="flex items-center gap-4 flex-1">
                    <button id="toggleSidebarButton"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium hover:bg-gray-100 h-10 w-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-menu h-5 w-5">
                            <line x1="4" x2="20" y1="12" y2="12"></line>
                            <line x1="4" x2="20" y1="6" y2="6"></line>
                            <line x1="4" x2="20" y1="18" y2="18"></line>
                        </svg>
                    </button>
                    <div class="relative max-w-md w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-search absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                        <input type="search"
                            class="w-full rounded-full border-none bg-gray-100 px-10 py-2 text-sm focus:ring-2 focus:ring-[hsl(217,91%,60%)]"
                            placeholder="Search..." />
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Notification -->
                    <button
                        class="relative h-10 w-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition">
                        <i class="fas fa-bell text-gray-700 text-lg"></i>
                        <span
                            class="absolute top-1 right-1 h-4 w-4 bg-blue-600 text-white text-[10px] rounded-full flex items-center justify-center">3</span>
                    </button>

                    <!-- Profile -->
                    <div class="relative">
                        <button id="profileButton"
                            class="flex items-center gap-2 hover:bg-gray-100 rounded-full px-2 py-1 transition">
                            <img src="https://i.pravatar.cc/40" alt="Profile"
                                class="h-8 w-8 rounded-full border border-gray-200" />
                            <span class="text-sm font-medium text-gray-800 hidden md:block">
                                {{ Auth::user()->name ?? 'Admin' }}
                            </span>
                            <i class="fas fa-chevron-down text-gray-500 text-xs hidden md:block"></i>
                        </button>

                        <!-- Dropdown -->
                        <div id="profileDropdown"
                            class="hidden absolute right-0 mt-3 w-60 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 transition-all duration-200">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ Auth::user()->name ?? 'Admin' }}
                                </p>
                                <p class="text-xs text-gray-500"> {{ Auth::user()->email ?? 'example@example.com' }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main Content Area -->
            <div class="flex-1 overflow-y-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>

