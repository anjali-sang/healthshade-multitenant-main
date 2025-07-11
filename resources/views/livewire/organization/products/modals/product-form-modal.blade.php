<x-modal name="fill-product-form" width="w-full" height="h-auto" maxWidth="4xl">
    <header class="bg-gray-50 dark:bg-gray-700 px-6 py-4 rounded-t-lg border-b border-gray-200 dark:border-gray-600">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                {{ __('Add to My Practice catalog') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Fill the product information to start using it for your practice.') }}
            </p>
        </div>
    </header>
    <form wire:submit.prevent="updateProduct" class="p-6 space-y-6">
        <div class="grid grid-cols-1 gap-6">
            <div class="col-span-1 space-y-4">
                <div>
                    <x-input-label for="category" :value="__('Product Category')" />
                    <div class="mt-1">
                        <select wire:model="category_id" id="category" class="block w-full rounded-md border-gray-300 dark:border-gray-700 
                                                shadow-sm focus:border-indigo-500 focus:ring-indigo-500 
                                                transition duration-300 ease-in-out">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        @error("category_id")
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="product_base_unit" :value="__('*Product Unit')" />
                        <x-text-input id="product_base_unit" wire:model="product_base_unit"
                            class="mt-1 block w-full bg-slate-200" disabled="true" />
                        @error('product_base_unit')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="product_cost" :value="__('*Product Base Cost')" />
                        <x-text-input id="product_cost" wire:model="product_cost" type="number" step="0.01"
                            class="mt-1 block w-full" required placeholder="0.00" />
                        @error('product_cost')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
            <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">
                {{ __('Location Inventory Settings') }}
            </h3>
            <div class="overflow-x-auto max-h-64 overflow-y-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800 sticky top-0">
                        <tr>
                            @foreach(['Location', 'Alert Qty', 'Par Qty'] as $header)
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($locations as $location)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition duration-150">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $location->name }}
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" wire:model="locationData.{{ $location->id }}.alert_quantity" class="w-full rounded-md border-gray-300 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Alert Quantity">
                                    @error("locationData.{$location->id}.alert_quantity")
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" wire:model="locationData.{{ $location->id }}.par_quantity" class="w-full rounded-md border-gray-300 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Par Quantity">
                                    @error("locationData.{$location->id}.par_quantity")
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <x-secondary-button type="button" @click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-primary-button type="submit" class="ml-3">
                {{ __('+ Add') }}
            </x-primary-button>
        </footer>
    </form>
</x-modal>