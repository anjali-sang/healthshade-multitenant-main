<x-modal name="add-product-to-cart" width="w-100" height="h-auto" maxWidth="4xl" class="z-9999 fixed">
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
                            <td class="p-3 font-medium text-gray-900 dark:text-gray-100">
                                {{ $product_name }}
                            </td>
                            <td class="p-3 font-medium text-gray-900 dark:text-gray-100">
                                {{ $location_name }}
                            </td>
                            <td class="p-3 hidden">
                                <span>${{ number_format($product_cost, 2) }}</span>
                            </td>
                            <td class="p-3">
                                <select id="unit" wire:model.live="unit_id" wire:change="updateFinalPrice"
                                    class="block w-full border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit['unit_id'] }}" {{ $unit['is_base_unit'] ? 'selected' : '' }}>
                                            {{ $unit['unit_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3">
                                <div class="flex items-center rounded-md dark:border-gray-600 overflow-hidden">
                                    <!-- Minus Button -->
                                    <button type="button" x-on:click="
                                                    $wire.set('quantity', Math.max(1, Number($wire.quantity) - 1), false);
                                                    clearTimeout(window.quantityTimer);
                                                    window.quantityTimer = setTimeout(() => $wire.updateFinalPrice(), 500);
                                                "
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4" />
                                        </svg>
                                    </button>

                                    <!-- Input without arrows -->
                                    <input type="number" min="1" max="100" wire:model.defer="quantity" x-on:change="
                                                    clearTimeout(window.quantityTimer);
                                                    window.quantityTimer = setTimeout(() => $wire.updateFinalPrice(), 500);
                                                "
                                        class="w-12 text-center border-0 focus:ring-0 dark:bg-gray-800 dark:text-gray-300 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                                    <!-- Plus Button -->
                                    <button type="button" x-on:click="
                                                    $wire.set('quantity', Math.min(100, Number($wire.quantity) + 1), false);
                                                    clearTimeout(window.quantityTimer);
                                                    window.quantityTimer = setTimeout(() => $wire.updateFinalPrice(), 500);
                                                "
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
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