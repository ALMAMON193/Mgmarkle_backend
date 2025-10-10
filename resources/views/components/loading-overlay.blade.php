<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 300)" x-show="show" x-transition.opacity.duration.500ms
    class="absolute inset-0 bg-gradient-to-br from-gray-100 via-gray-50 to-gray-200 bg-opacity-90 z-50 flex items-center justify-center transition-all duration-500">

    <div
        class="relative flex flex-col items-center space-y-6 p-8 bg-white rounded-3xl shadow-2xl border border-gray-200">
        <!-- Spinner -->
        <div class="relative w-16 h-16">
            <svg class="animate-spin-slow w-full h-full text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-80" fill="currentColor" d="M12 2a10 10 0 0110 10h-2a8 8 0 00-8-8V2z"></path>
            </svg>
            <!-- Glow effect -->
            <span class="absolute inset-0 rounded-full bg-indigo-500 opacity-20 animate-ping"></span>
        </div>

        <!-- Message -->
        <p class="text-xl font-semibold text-gray-700 tracking-wide animate-pulse">
            Loading, please wait...
        </p>

        <!-- Progress Bar -->
        <div class="relative w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
            <div class="absolute h-full bg-gradient-to-r from-indigo-400 via-indigo-600 to-indigo-500 animate-loading-bar"
                style="width: 60%;"></div>
        </div>
    </div>
    <style>
        /* Smooth spinner */
        .animate-spin-slow {
            animation: spin 1.5s linear infinite;
        }

        /* Loading bar animation */
        @keyframes loading-bar {
            0% {
                transform: translateX(-100%);
            }

            50% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .animate-loading-bar {
            animation: loading-bar 1.5s infinite;
        }
    </style>
</div>
