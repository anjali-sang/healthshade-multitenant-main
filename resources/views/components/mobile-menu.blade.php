<div x-data="{ open: false }" class="md:hidden">
    <!-- Hamburger Button (always on left) -->
    <button
        @click="open = true"
        class="fixed top-4 left-4 z-50 p-2 rounded-md bg-white dark:bg-gray-800 shadow-md text-gray-700 dark:text-gray-300"
    >
        <!-- Hamburger Icon -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Drawer -->
    <div
        x-show="open"
        x-transition:enter="transition ease-in-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-lg z-40 transform -translate-x-full overflow-y-auto"
        @click.away="open = false"
    >
        <div class="flex justify-between items-center px-4 py-4 border-b border-gray-200 dark:border-gray-700">
            <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">Menu</span>
            <button @click="open = false" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="py-4">
            @foreach ($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-4 px-4 py-3 text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <span class="w-6 h-6">{!! $item['svg'] !!}</span>
                    <span class="text-base">{{ __($item['title']) }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Dark Overlay -->
    <div
        x-show="open"
        class="fixed inset-0 bg-black bg-opacity-50 z-30"
        @click="open = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>
</div>
