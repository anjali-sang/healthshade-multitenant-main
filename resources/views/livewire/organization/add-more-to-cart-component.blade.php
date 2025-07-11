<div class="relative w-full mb-4">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" id="productSearch" placeholder="Add products in the cart ..." wire:model.live="searchTerm" autocomplete="off"
            class="pl-10 w-full px-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-primary-md focus:border-primary-md dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            @if($searchTerm)
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <button wire:click="clearSearch" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>
            @endif
        @if($searchTerm && count($searchResults) > 0)
            <div
                class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                <ul class="max-h-60 py-1 overflow-auto text-sm">
                    @foreach($searchResults as $product)
                        <li wire:key="product-{{ $product->id }}"
                            class="px-4 py-2 flex items-center">
                            @if($product->image)
                                @php    
                                    $images = json_decode($product->image, true);
            $imagePath = is_array($images) && !empty($images) ? $images[0] : $product->image; 
                                @endphp
                                <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}" class="w-8 h-8 mr-3 object-cover rounded">
                            @endif
                            <div>
                                <p class="font-medium dark:text-gray-100">({{ $product->product_code }}){{ $product->product_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-300">${{ number_format($product->cost, 2) }}</p>
                            </div>
                            <div class="bg-primary-md ms-auto p-2 rounded text-white cursor-pointer hover:bg-primary-dk" wire:click="addToCart({{ $product->id }})">
                                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
                                </svg>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @elseif($searchTerm && count($searchResults) == 0)
            <div
                class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                    No products found.
                </div>
            </div>
        @endif

        <x-modal name="add-product-to-cart" width="w-100" height="h-auto" maxWidth="4xl">
            <header class="p-3 border-b border-gray-300 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Add to Cart') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Review the details below before adding to the cart.') }}
                </p>
            </header>
            <form wire:submit.prevent="addProductToCart" class="bg-gray-50 dark:bg-gray-800">
                <div class="py-3">
                    <div class="overflow-hidden bg-white dark:bg-gray-900 shadow rounded-lg">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                            <thead class="text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="p-3">{{ __('Product Name') }}</th>
                                    <th class="p-3">{{ __('Clinic') }}</th>
                                    <th class="p-3 hidden">{{ __('Base Price') }}</th>
                                    <th class="p-3">{{ __('Unit') }}</th>
                                    <th class="p-3">{{ __('Quantity') }}</th>
                                    <th class="p-3 hidden">{{ __('Final Price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-gray-50 dark:bg-gray-800">
                                    <td class="p-3 font-medium text-gray-900 dark:text-gray-100 text-wrap">
                                        {{ $product_name }}
                                    </td>
                                    <td class="p-3 font-medium text-gray-900 dark:text-gray-100">
                                        {{ $location_name }}
                                    </td>
                                    <td class="p-3 hidden">
                                        <span>${{ number_format($product_cost, 2) }}</span>
                                    </td>
                                    <td class="p-3">
                                        <select
                                            id="unit"
                                            wire:model.live="unit_id"
                                            wire:change="updateFinalPrice"
                                            class="block w-full border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600"
                                        >
                                            @foreach ($units as $unit)
                                                <option
                                                    value="{{ $unit['unit_id'] }}"
                                                    {{ $unit['is_base_unit'] ? 'selected' : '' }}
                                                >
                                                    {{ $unit['unit_name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    <div class="flex items-center rounded-md dark:border-gray-600 overflow-hidden">
                                            <!-- Minus Button -->
                                            <button type="button" x-on:click="
                                                    $wire.set('addToCartQty', Math.max(1, Number($wire.addToCartQty) - 1), false);
                                                    clearTimeout(window.quantityTimer);
                                                    window.quantityTimer = setTimeout(() => $wire.updateFinalPrice(), 500);
                                                "
                                                class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                    
                                            <!-- Input without arrows -->
                                            <input type="number" min="1" max="100" wire:model.defer="addToCartQty" x-on:change="
                                                    clearTimeout(window.quantityTimer);
                                                    window.quantityTimer = setTimeout(() => $wire.updateFinalPrice(), 500);
                                                " class="w-12 text-center border-0 focus:ring-0 dark:bg-gray-800 dark:text-gray-300 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    
                                            <!-- Plus Button -->
                                            <button type="button" x-on:click="
                                                    $wire.set('addToCartQty', Math.min(100, Number($wire.addToCartQty) + 1), false);
                                                    clearTimeout(window.quantityTimer);
                                                    window.quantityTimer = setTimeout(() => $wire.updateFinalPrice(), 500);
                                                "
                                                class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    
                                    <td class=" hidden p-3 font-bold text-lg text-green-600 dark:text-green-400">
                                        <span>{{ number_format($total, 2) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex justify-end gap-4 p-4 border-t border-gray-300 dark:border-gray-700">
                    <x-primary-button class="px-6 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __('Add to Cart') }}</span>
                        <span wire:loading>{{ __('Processing...') }}</span>
                    </x-primary-button>
                    <x-secondary-button x-on:click="$dispatch('close-modal', 'add-product-to-cart')"
                        class="px-6 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                </div>
            </form>
        </x-modal>

        <!-- Notifications Container -->
        <div class="fixed top-24 right-4 z-50 space-y-2">
            @foreach ($notifications as $notification)
                <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }" x-init="setTimeout(() => {
                            show = false;
                            $wire.removeNotification('{{ $notification['id'] }}');
                        }, 3000)" x-show="show" x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
                    class="{{ $notification['type'] === 'success' ? 'text-white bg-green-400' : 'text-white bg-red-400' }} border-l-4 x-6 py-6 px-4 rounded-lg shadow-lg">
                    <p>{{ $notification['message'] }}</p>
                </div>
            @endforeach
        </div>
        
    </div>