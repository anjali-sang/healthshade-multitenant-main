<div class="max-w-10xl mx-auto px-4">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-5">
        <section class="w-full border-b-2 pb-4 mb-6">
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Inventory Adjustment') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Adjust your inventory stats.') }}
                    </p>
                </div>
                <div>
                    <!-- Location dropdown -->
                </div>
            </header>
        </section>
        <div class="text-xs">
            <livewire:tables.user.inventory-adjust-list />
        </div>
    </div>
    <div>
        <x-modal name="adjust_product_modal" width="w-100" height="h-auto" maxWidth="4xl" wire:model="showModal">
            <header class="flex items-center justify-between p-3 border-b border-gray-300 dark:border-gray-700">
                <h2 class="font-semibold text-lg text-gray-600 dark:text-gray-100">
                    Inventory Adjustment
                </h2>
                <h2 class="font-bold text-lg text-gray-600 dark:text-gray-100">
                    {{ $inventoryAdjust ?? '' }}
                </h2>
            </header>
            <form wire:submit.prevent="updateAdjustment">
                <div class="bg-white p-4">
                    @if ($selectedProduct && $selectedProduct->product)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr class="text-left text-gray-700 dark:text-gray-300">
                                        <th class="p-3 border-b">Product Name</th>
                                        <th class="p-3 border-b">Unit</th>
                                        <th class="p-3 border-b">On Hand Quantity</th>
                                        <th class="p-3 border-b">Action</th>
                                        <th class="p-3 border-b">Adjust Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-gray-900 dark:text-gray-200">
                                        <td class="p-3 border-b font-semibold">
                                            {{ $selectedProduct->product->product_name }}
                                            ({{ $selectedProduct->product->product_code }})
                                        </td>
                                        <td class="p-3 border-b">
                                            {{ $selectedProduct->product->units[0]->unit->unit_name }}
                                        </td>
                                        <td class="p-3 border-b font-medium">
                                            {{ $selectedProduct->on_hand_quantity }}
                                        </td>
                                        <td class="p-3 border-b">
                                            <div class="flex items-center space-x-2">
                                                <select wire:model.live="adjustType"
                                                    class="w-24 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md focus:ring focus:ring-blue-400 bg-gray-100 dark:bg-gray-700 dark:text-white">
                                                    <option value="add">Add (+)</option>
                                                    <option value="subtract">Sub (-)</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="p-3 border-b">
                                            <input type="number" min="0" wire:model.live="adjustQty"
                                                class="w-24 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md focus:ring focus:ring-blue-400 bg-gray-100 dark:bg-gray-700 dark:text-white">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @if(isset($adjustType) && isset($adjustQty) && $adjustQty > 0)
                            <div class="mt-2 text-sm text-end">
                                <span
                                    class="font-medium {{ $adjustType === 'add' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $adjustType === 'add' ? 'Adding' : 'Subtracting' }} {{ $adjustQty }}
                                    units
                                    (New total:
                                    {{ $adjustType === 'add' ? $selectedProduct->on_hand_quantity + $adjustQty : $selectedProduct->on_hand_quantity - $adjustQty }})
                                </span>

                                @if($adjustType === 'subtract' && $adjustQty > $selectedProduct->on_hand_quantity)
                                    <p class="text-red-600 dark:text-red-400 mt-1">
                                        This will result in negative inventory!
                                    </p>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="text-red-500 text-center font-medium p-3">Product not found.</div>
                    @endif

                    <div class="flex justify-end gap-4 p-4">
                        <x-secondary-button type="button"
                            class="px-6 py-2 text-gray-600 bg-gray-200 hover:bg-gray-300 rounded-lg transition-all"
                            wire:click="cancelAdjustment">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button
                            class="px-6 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ __('Adjust') }}</span>
                            <span wire:loading>{{ __('Processing...') }}</span>
                        </x-primary-button>

                    </div>
                </div>
            </form>

        </x-modal>
    </div>
    <!-- Notifications Container -->
    <div class="fixed top-24 right-4 z-50 space-y-2">
        @foreach ($notifications as $notification)
            <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }"
                x-init="setTimeout(() => {
                                                                                                                                                    show = false;
                                                                                                                                                    $wire.removeNotification('{{ $notification['id'] }}');
                                                                                                                                                }, 3000)" x-show="show"
                x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
                class="{{ $notification['type'] === 'success' ? 'text-white bg-green-400' : 'text-white bg-red-400' }} border-l-4 x-6 py-6 px-4 rounded-lg shadow-lg">
                <p>{{ $notification['message'] }}</p>
            </div>
        @endforeach
    </div>
</div>