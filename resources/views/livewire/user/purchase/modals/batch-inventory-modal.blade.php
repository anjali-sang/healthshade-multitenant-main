<x-modal name="batch_inventory_modal" width="w-100" height="h-auto" maxWidth="4xl" wire:model="showBatchInventoryModal">
    <header class="p-3 border-b border-gray-300 dark:border-gray-700">
        <h2 class="font-semibold text-lg font-medium text-gray-900 dark:text-gray-100">
            Batch Inventory Management
        </h2>
    </header>
    <div class="p-4">
        @if(isset($batchInventory) && count($batchInventory) > 0)
            <form wire:submit.prevent="saveBatchInventory">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Product</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Quantity</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Batch Number</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($batchInventory as $index => $item)
                                @php
                                    $product = \App\Models\Product::find($item['product_id']);
                                    $receivedQty = $item['received_quantity'];
                                @endphp

                                @for($i = 0; $i < $receivedQty; $i++)
                                    <tr>
                                        <td class="px-6 py-4 text-wrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $product->product_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <span class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-md">Unit {{ $i + 1 }} of
                                                {{ $receivedQty }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <input type="text" wire:model="batchDetails.{{ $index }}.{{ $i }}.batch_number"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                placeholder="Batch Number" required>
                                            @if($i != 0)
                                                <!-- <div class="mt-1 text-xs text-gray-500">
                                                                <div class="flex items-center">
                                                                    <input id="same-batch-{{ $index }}-{{ $i }}" type="checkbox" wire:click="copyPreviousBatchDetails({{ $index }}, {{ $i }})" class="mr-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                                                    <label for="same-batch-{{ $index }}-{{ $i }}">Same as previous unit</label>
                                                                </div>
                                                            </div> -->
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <input type="date" wire:model="batchDetails.{{ $index }}.{{ $i }}.expiry_date"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                required>
                                        </td>
                                    </tr>
                                @endfor
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end space-x-3">
                    <button type="button" wire:click="closeBatchInventoryModal"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Save Batch Details
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-4">
                <p class="text-gray-500 dark:text-gray-400">No batch inventory items found.</p>
            </div>
        @endif
    </div>

</x-modal>