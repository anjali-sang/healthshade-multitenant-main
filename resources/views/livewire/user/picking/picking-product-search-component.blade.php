<div class="col-span-3 bg-white dark:bg-gray-800 shadow rounded-lg p-4 h-[500px] flex flex-col">
    <!-- Search Bar -->
    <div class="relative">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
        </div>
        <input type="search" wire:model.live.debounce.300ms="search"
            class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Add products..." />
    </div>

    <!-- Product List -->
    <ul class="mt-4 flex-1 overflow-y-auto">
        @if($products)
        @forelse ($products as $product)
            <li wire:click="dispatchProductSelection({{ $product->product->id }})"
                class="p-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 border-b border-slate-300 dark:border-slate-700 text-gray-700 dark:text-gray-300">
                <span class="font-small">({{ $product->product->product_code }})</span>
                <span class="font-small ml-1">{{ $product->product->product_name }}</span>
            </li>
        @empty
            <li class="p-4 text-center text-gray-500 dark:text-gray-400">
                No products found
            </li>
        @endforelse
        @endif
    </ul>
</div>