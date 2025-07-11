<x-modal name="receive_product_model" width="w-100" height="h-auto" maxWidth="6xl" wire:model="showModal">
    <header class="p-3 dark:border-gray-700">
        <h2 class="font-semibold text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $purchaseOrder->purchase_oder_number ?? '' }}
        </h2>
    </header>

    <form wire:submit.prevent="updateReceiveQuantity" class="bg-gray-50 dark:bg-gray-800">
        <div class="max-h-96 overflow-y-auto">
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                <!-- Table Header -->
                <div
                    class="bg-gray-100 dark:bg-gray-800 px-6 py-3 border-b border-gray-200 dark:border-gray-700 rounded-t-lg">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 text-sm font-medium text-gray-700 dark:text-gray-300">

                        <div class="col-span-3">Product Details</div>
                        <div class="col-span-2">Order Info</div>
                        <div class="col-span-2">Lot/Batch</div>
                        <div class="col-span-2">Expiry Date</div>
                        <div class="col-span-2">Receive Qty</div>
                        <div class="col-span-1"></div>
                    </div>
                </div>

                <!-- Product List -->
                @if ($purchaseOrder?->purchasedProducts)
                    @foreach($purchaseOrder->purchasedProducts as $product)
                        {{-- if Lot numbers are enabled for the product --}}
                        @if($product->product->has_expiry_date)
                            <!-- Products with Lot Numbers -->
                            @if(isset($productLots[$product->id]))
                                @foreach($productLots[$product->id] as $lotIndex => $lot)
                                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800"
                                        wire:key="lot-{{ $product->id }}-{{ $lotIndex }}">
                                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 text-sm font-medium text-gray-700 dark:text-gray-300">


                                            <!-- Product Details -->
                                            <div class="col-span-3">
                                                @if($lotIndex === 0)
                                                    <h3 class="font-medium text-gray-900 dark:text-white text-sm">
                                                        ({{ $product->product->product_code}}) {{ $product->product->product_name}}
                                                    </h3>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        Unit: {{ $product->unit->unit_name }}
                                                    </p>
                                                @else
                                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $product->product->product_code}} (Lot {{ $lotIndex + 1 }})
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Order Info -->
                                            <div class="col-span-2 text-sm text-gray-600 dark:text-gray-400">
                                                @if($lotIndex === 0)
                                                    <div>Ordered: {{ $product->quantity }}</div>
                                                    <div>Received: {{ $product->received_quantity }}</div>
                                                    <div>Remaining: {{ $product->quantity - $product->received_quantity }}</div>
                                                @endif
                                            </div>

                                            <!-- Lot Number -->
                                            <div class="col-span-2">
                                                <input type="text" wire:model="productLots.{{ $product->id }}.{{ $lotIndex }}.batch_number"
                                                    class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                    placeholder="Lot number">
                                                @error("productLots.{$product->id}.{$lotIndex}.batch_number")
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Expiry Date (Month and Year Only) -->
                                            <div class="col-span-2">
                                                <input type="month" wire:model="productLots.{{ $product->id }}.{{ $lotIndex }}.expiry_date"
                                                    class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                @error("productLots.{$product->id}.{$lotIndex}.expiry_date")
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <!-- Quantity Controls -->
                                            <div class="col-span-2">
                                                <div class="flex items-center">
                                                    <button type="button"
                                                        wire:click="decrementQuantity({{ $product->id }}, {{ $lotIndex }})"
                                                        class="flex items-center justify-center w-7 h-7 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-l-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M20 12H4" />
                                                        </svg>
                                                    </button>

                                                    <input type="number"
                                                        wire:model="productLots.{{ $product->id }}.{{ $lotIndex }}.quantity" min="0"
                                                        class="w-12 px-1 py-1 text-center text-sm border-t border-b border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-400 bg-gray-100 dark:bg-gray-700 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                                                    <button type="button"
                                                        wire:click="incrementQuantity({{ $product->id }}, {{ $lotIndex }})"
                                                        class="flex items-center justify-center w-7 h-7 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-r-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @error("productLots.{$product->id}.{$lotIndex}.quantity")
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                                @error("productLots.{$product->id}")
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Actions -->
                                            <div class="col-span-1 flex justify-end">
                                                @if($lotIndex === 0)
                                                    <button type="button" wire:click="addLot({{ $product->id }})"
                                                        class="px-2 py-1 text-xs bg-blue-100 hover:bg-blue-200 text-blue-600 rounded border border-blue-300 focus:outline-none focus:ring-1 focus:ring-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800 dark:text-blue-300 dark:border-blue-600"
                                                        title="Add Lot">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button type="button" wire:click="removeLot({{ $product->id }}, {{ $lotIndex }})"
                                                        class="px-2 py-1 text-xs bg-red-100 hover:bg-red-200 text-red-600 rounded border border-red-300 focus:outline-none focus:ring-1 focus:ring-red-400 dark:bg-red-900 dark:hover:bg-red-800 dark:text-red-300 dark:border-red-600"
                                                        title="Remove Lot">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M20 12H4" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Single lot entry for products with expiry -->
                                <div
                                    class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 text-sm font-medium text-gray-700 dark:text-gray-300">


                                        <!-- Product Details -->
                                        <div class="col-span-3">
                                            <h3 class="font-medium text-gray-900 dark:text-white text-sm">
                                                ({{ $product->product->product_code}}) {{ $product->product->product_name}}
                                            </h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Unit: {{ $product->unit->unit_name }}
                                            </p>
                                        </div>

                                        <!-- Order Info -->
                                        <div class="col-span-2 text-sm text-gray-600 dark:text-gray-400">
                                            <div>Ordered: {{ $product->quantity }}</div>
                                            <div>Received: {{ $product->received_quantity }}</div>
                                            <div>Remaining: {{ $product->quantity - $product->received_quantity }}</div>
                                        </div>

                                        <!-- Lot Number -->
                                        <div class="col-span-2">
                                            <input type="text" wire:model="batchDetails.{{ $product->product->id }}.batch_number"
                                                class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                placeholder="Lot number">
                                        </div>

                                        <!-- Expiry Date -->
                                        <div class="col-span-2">
                                            <input type="month" wire:model="batchDetails.{{ $product->product->id }}.expiry_date"
                                                class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="col-span-2">
                                            <div class="flex items-center">
                                                <button type="button" wire:click="decrementQuantity({{ $product->id }})"
                                                    class="flex items-center justify-center w-7 h-7 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-l-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M20 12H4" />
                                                    </svg>
                                                </button>

                                                <input type="number" wire:model="receivedQuantities.{{ $product->id }}"
                                                    class="w-12 px-1 py-1 text-center text-sm border-t border-b border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-400 bg-gray-100 dark:bg-gray-700 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                                                <button type="button" wire:click="incrementQuantity({{ $product->id }})"
                                                    class="flex items-center justify-center w-7 h-7 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-r-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                            @error("receivedQuantities.{$product->id}")
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-span-1 flex justify-end">
                                            <button type="button" wire:click="addLot({{ $product->id }})"
                                                class="px-2 py-1 text-xs bg-blue-100 hover:bg-blue-200 text-blue-600 rounded border border-blue-300 focus:outline-none focus:ring-1 focus:ring-blue-400 dark:bg-blue-900 dark:hover:bg-blue-800 dark:text-blue-300 dark:border-blue-600"
                                                title="Add Lot">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    @error("productLots.{$product->id}")
                                        <span class="text-red-500 text-xs px-6">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        @else
                            <!-- Products without Lot Numbers -->
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 sm:gap-4 text-sm font-medium text-gray-700 dark:text-gray-300">


                                    <!-- Product Details -->
                                    <div class="col-span-3">
                                        <h3 class="font-medium text-gray-900 dark:text-white text-sm">
                                            ({{ $product->product->product_code}}) {{ $product->product->product_name}}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Unit: {{ $product->unit->unit_name }}
                                        </p>
                                    </div>

                                    <!-- Order Info -->
                                    <div class="col-span-2 text-sm text-gray-600 dark:text-gray-400">
                                        <div>Ordered: {{ $product->quantity }}</div>
                                        <div>Received: {{ $product->received_quantity }}</div>
                                        <div>Remaining: {{ $product->quantity - $product->received_quantity }}</div>
                                    </div>

                                    <!-- Lot Number (N/A) -->
                                    <div class="col-span-2 text-sm text-gray-400 dark:text-gray-500">
                                        N/A
                                    </div>

                                    <!-- Expiry Date (N/A) -->
                                    <div class="col-span-2 text-sm text-gray-400 dark:text-gray-500">
                                        N/A
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="col-span-2">
                                        <div class="flex items-center">
                                            <button type="button" wire:click="decrementQuantity({{ $product->id }})"
                                                class="flex items-center justify-center w-7 h-7 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-l-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 12H4" />
                                                </svg>
                                            </button>

                                            <input type="number" wire:model="receivedQuantities.{{ $product->id }}"
                                                class="w-12 px-1 py-1 text-center text-sm border-t border-b border-gray-300 dark:border-gray-600 focus:ring focus:ring-blue-400 bg-gray-100 dark:bg-gray-700 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                                            <button type="button" wire:click="incrementQuantity({{ $product->id }})"
                                                class="flex items-center justify-center w-7 h-7 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-r-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                        @error("receivedQuantities.{$product->id}")
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Actions (Empty for non-expiry products) -->
                                    <div class="col-span-1"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div class="flex justify-end gap-4 p-4 border-t border-gray-300 dark:border-gray-700">
            <x-primary-button class="px-6 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg"
                wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('Receive') }}</span>
                <span wire:loading>{{ __('Processing...') }}</span>
            </x-primary-button>
        </div>
    </form>
</x-modal>