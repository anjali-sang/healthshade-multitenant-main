<div>

    @if ($cartCount > 0)
    <button
    class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
    wire:click="goToCart">
    <span class="text-lg">🛒</span>
    <span
    class="absolute top-0 right-0 -mt-2 -mr-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
    {{ $cartCount }}
</span>
</button>
@endif
    </div>
