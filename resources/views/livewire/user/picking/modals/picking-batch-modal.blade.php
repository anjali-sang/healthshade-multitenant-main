<x-modal name="picking_batch_modal" width="w-100" height="h-auto" maxWidth="6xl" wire:model="showModal">
    <header class="p-3 border-b border-gray-300 dark:border-gray-700 flex items-center justify-between">
        <h2 class="font-semibold text-lg font-medium text-gray-600 dark:text-gray-100">
            {{__('Batch Pickings')}}
        </h2>
        <div class="p-3 font-semibold dark:text-gray-100">
            {{ $pickingNumber ?? '' }}
        </div>
    </header>

    <form wire:submit.prevent="updateBatchPicking">
        <div class="dark:bg-gray-800 bg-white">
            @if ($selectedProduct && $selectedProduct->product)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr class="text-left text-gray-700 dark:text-gray-300">
                                <th class="p-3 border-b">Batch</th>
                                <th class="p-3 border-b">Product Name</th>
                                <th class="p-3 border-b">Unit</th>
                                <th class="p-3 border-b">{{__('Available')}}</th>
                                <th class="p-3 border-b">{{__('Expiry')}}</th>
                                @if ($selectedProduct->product->categories?->category_name == 'biological')
                                    <th class="p-3 border-b">{{__(key: 'Chart number')}}</th>
                                @endif
                                <th class="p-3 border-b">Pick Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-gray-900 dark:text-gray-200">
                                <td class="p-3 border-b font-semibold text-wrap">
                                    {{ $selectedProduct->batch_number }}
                                </td>
                                <td class="p-3 border-b font-semibold text-wrap">
                                    {{ $selectedProduct->product->product_name }}
                                    ({{ $selectedProduct->product->product_code }})
                                </td>
                                <td class="p-3 border-b">
                                    {{ $selectedProduct->product->units[0]->unit->unit_name }}
                                </td>
                                <td class="p-3 border-b font-medium">
                                    {{ $selectedProduct->quantity }}
                                </td>
                                <td class="p-3 border-b text-nowrap">
                                    {{ \Carbon\Carbon::parse($selectedProduct->expiry_date)->format('m/Y') }}
                                </td>
                                @if($selectedProduct->product->categories?->category_name == 'biological')
                                    <td class="p-3 border-b">
                                        <input type="text" wire:model="chart_number"
                                            class="mt-1 p-2 w-full border rounded-md shadow-sm dark:bg-gray-800"
                                            placeholder="Enter chart number">
                                    </td>
                                @endif
                                <td class="p-3 border-b">
                                    <div class="flex items-center">
                                        <button type="button" wire:click="decrementQuantity"
                                            class="flex items-center justify-center w-8 h-8 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-l-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </button>

                                        <input type="number" min="0" wire:model.live="pickQuantity"
                                            max="{{ $selectedProduct->on_hand_quantity }}"
                                            class="w-16 px-2 py-1 text-center border-t border-b border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-400 bg-gray-100 dark:bg-gray-700 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                                        <button type="button" wire:click="incrementQuantity"
                                            class="flex items-center justify-center w-8 h-8 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-r-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-red-500 text-center font-medium p-3">Product not found.</div>
            @endif

            <div class="flex justify-end gap-4 p-4">
                <x-secondary-button type="button"
                    class="px-6 py-2 text-gray-600 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all"
                    wire:click="cancelPicking">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button class="px-6 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('Pick') }}</span>
                    <span wire:loading>{{ __('Processing...') }}</span>
                </x-primary-button>
            </div>
        </div>
    </form>

</x-modal>