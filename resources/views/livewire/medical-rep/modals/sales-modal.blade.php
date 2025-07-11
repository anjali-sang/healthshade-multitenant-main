{{-- <!-- Modal -->
<x-modal name="medical-sale-modal" width="w-full" maxWidth="7xl" class="overflow-y-auto">
    <div>
        @if($viewMode)
            <!-- Header Section -->
            <div
                class="flex items-start justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ __('Sale Details') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('View your drafts and sale data in read-only mode.') }}
                    </p>
                </div>
                @if($sale?->status != 'completed')
                    <button wire:click="openSaleModal({{ $saleId }})" class="text-gray-500 hover:text-blue-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.28 6.4L11.74 15.94C10.79 16.89 7.97 17.33 7.34 16.7C6.71 16.07 7.14 13.25 8.09 12.3L17.64 2.75C18.92 1.47 21.52 3.03 21.28 6.4Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 4H6C4.94 4 3.92 4.42 3.17 5.17C2.42 5.92 2 6.94 2 8V18C2 19.06 2.42 20.08 3.17 20.83C3.92 21.58 4.94 22 6 22H17C19.21 22 20 20.2 20 18V13" />
                        </svg>
                    </button>
                @endif
            </div>

            <!-- sale Info Grid -->
            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sales Number</p>
                    <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                        {{ $sale_number ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Customer</p>
                    <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                        {{ optional($organizations->find($organization_id))->name ?? '-' }}
                        <span
                            class="text-sm text-gray-500">({{ optional($organizations->find($organization_id))->email ?? '-' }})</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Location</p>
                    <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                        {{ optional($locations->find($location_id))->name ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- sale Products Table -->
            <div class="mt-6 border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Quantity</th>
                            <th class="px-4 py-3">Unit</th>
                            <th class="px-4 py-3">Unit Price</th>
                            <th class="px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($saleProducts as $product)
                            <tr>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                    {{ $product['product_search'] ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                    {{ $product['quantity'] ?? '0' }}
                                </td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                    {{ $product['sale_unit'] ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                    ${{ number_format($product['net_unit_price'] ?? 0, 2) }}
                                </td>
                                <td class="px-4 py-2 text-gray-800 dark:text-gray-200 font-medium">
                                    ${{ number_format($product['total_price'] ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Totals -->
            <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg mb-6 mt-3">
                <div class="grid grid-cols-1 md:grid-cols-3 text-center gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Quantity</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ number_format($total_quantity, 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Price</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($total_price, 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Grand Total</p>
                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                            ${{ number_format($grand_total, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            @if($sale?->status != 'completed')
                <div
                    class="flex justify-end space-x-3 bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-md transition-colors">
                        Cancel
                    </button>
                    <x-primary-button type="shipProducts" wire:click="confirmSale"
                        class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span>
                            {{ __('Send shipment') }}
                        </span>
                    </x-primary-button>
                </div>
            @endif

        @else
            <!-- Header -->
            <div
                class="flex items-start justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $editMode ? __('Edit Sales') : __('Create New Sales') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Create a new shippment.') }}
                    </p>
                </div>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Loading Overlay -->
            <div wire:loading.flex wire:target="save,loadSale"
                class="absolute inset-0 z-50 bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm items-center justify-center flex">
                <div class="flex items-center space-x-3">
                    <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.48 0 0 6.48 0 12h4z" />
                    </svg>
                    <span class="text-gray-700 dark:text-gray-200 text-sm font-medium">Processing...</span>
                </div>
            </div>

            <!-- Global Error Messages -->
            @if ($errors->any())
                <div class="mx-6 mt-4 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-400 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Please correct the following errors:
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal Body -->
            <form wire:submit.prevent="save" class="max-h-[75vh] overflow-y-auto px-6 py-6 bg-white dark:bg-gray-900">
                <!-- sale Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Sales Number</label>
                        <input type="text" wire:model="sale_number" readonly
                            class="w-full mt-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white text-sm shadow-sm" />
                        @error('sale_number')
                            <p class="text-xs text-red-600 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Organization<span
                                class="text-red-500">*</span></label>
                        <select wire:model.live="organization_id"
                            class="w-full mt-1 px-3 py-2 text-sm border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('organization_id') border-red-500 bg-red-50 dark:bg-red-900/20 @enderror">
                            <option value="">Select organization</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}">
                                    {{ $org->name . ' (' . $org->email . ')' }}
                                </option>
                            @endforeach
                        </select>
                        @error('organization_id')
                            <p class="text-xs text-red-600 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Location <span
                                class="text-red-500">*</span></label>
                        @if(!$organization_id)
                            <p class="text-sm text-gray-600 mt-3">
                                Please select an organization first.
                            </p>
                        @else
                            <select wire:model.live="location_id"
                                class="w-full mt-1 px-3 py-2 text-sm border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('location_id') border-red-500 bg-red-50 dark:bg-red-900/20 @enderror">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        @endif

                        @error('location_id')
                            <p class="text-xs text-red-600 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                @if($location_id != null)
                    <!-- Products -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Products</h4>
                            <button type="button" wire:click="addProductRow"
                                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition">
                                + Add Product
                            </button>
                        </div>

                        <!-- Products Array Error -->
                        @error('saleProducts')
                            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-md">
                                <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            </div>
                        @enderror

                        <!-- Additional validation for minimum products -->
                        @if(empty($saleProducts) || count($saleProducts) == 0)
                            @error('saleProducts.required')
                                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-md">
                                    <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        @endif

                        <div class="border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="p-2">Product</th>
                                        <th class="p-2">Quantity</th>
                                        <th class="p-2">Unit</th>
                                        <th class="p-2">Unit Price</th>
                                        <th class="p-2">Total</th>
                                        <th class="p-2 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($saleProducts as $index => $product)
                                        <tr
                                            class="@if($errors->has('saleProducts.' . $index . '.*')) bg-red-25 dark:bg-red-900/10 @endif">
                                            <!-- Product Selection -->
                                            <td class="p-2">
                                                <div>
                                                    <input type="text" wire:model.live="saleProducts.{{ $index }}.product_search"
                                                        wire:focus="showDropdown({{ $index }})" wire:blur="hideDropdown"
                                                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('saleProducts.' . $index . '.product_id') border-red-500 bg-red-50 dark:bg-red-900/20 @enderror"
                                                        autocomplete="off" placeholder="Search product..." />

                                                    <input type="hidden" wire:model="saleProducts.{{ $index }}.product_id" />

                                                    <!-- Product Selection Error -->
                                                    @error('saleProducts.' . $index . '.product_id')
                                                        <p class="text-xs text-red-600 mt-1 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            {{ $message }}
                                                        </p>
                                                    @enderror

                                                    <!-- Dropdown List -->
                                                    @if($show_dropdown_index == $index && count($filtered_products) > 0)
                                                        <div
                                                            class="z-[99] w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60">
                                                            @foreach($filtered_products as $product)
                                                                <div wire:click="selectProduct({{ $index }}, {{ $product->id }}, '{{ $product->product_name }}')"
                                                                    class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                                                                    {{ $product->product_name . ' (' . $product->product_code . ')' }}
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Quantity Input -->
                                            <td class="p-2">
                                                <input type="number" wire:model="saleProducts.{{ $index }}.quantity"
                                                    wire:change="calculateRowTotal({{ $index }})"
                                                    class="w-full px-2 py-1 text-sm border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('saleProducts.' . $index . '.quantity') border-red-500 bg-red-50 dark:bg-red-900/20 @enderror"
                                                    min="0" step="1" placeholder="Quantity">

                                                @error('saleProducts.' . $index . '.quantity')
                                                    <p class="text-xs text-red-600 mt-1 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </td>

                                            <!-- Unit Selection -->
                                            <td class="p-2">
                                                <div class="w-full text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white ">
                                                    {{ $saleProducts[$index]['sale_unit'] ?? null}}
                                                </div>

                                                @error('saleProducts.' . $index . '.sale_unit')
                                                    <p class="text-xs text-red-600 mt-1 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </td>

                                            <!-- Unit Price Display -->
                                            <td class="p-2">
                                                <div
                                                    class="px-2 py-1 text-sm border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white bg-gray-50 dark:bg-gray-800">
                                                    ${{ number_format($saleProducts[$index]['net_unit_price'] ?? 0, 2) }}
                                                </div>

                                                @error('saleProducts.' . $index . '.net_unit_price')
                                                    <p class="text-xs text-red-600 mt-1 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </td>

                                            <!-- Total Price Display -->
                                            <td class="p-2 text-gray-800 dark:text-white font-medium">
                                                ${{ number_format($product['total_price'] ?? 0, 2) }}

                                                @error('saleProducts.' . $index . '.total_price')
                                                    <p class="text-xs text-red-600 mt-1 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </td>

                                            <!-- Remove Button -->
                                            <td class="p-2 text-center">
                                                @if(count($saleProducts) > 1)
                                                    <button type="button" wire:click="removeProductRow({{ $index }})"
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-1 rounded transition-colors"
                                                        title="Remove this product">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 text-xs"></span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                    <p class="text-sm">No products added</p>
                                                    <p class="text-xs mt-1">Click "Add Product" to get started</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Totals -->
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 text-center gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Quantity</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ number_format($total_quantity, 2) }}
                            </p>
                            @error('total_quantity')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Price</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                ${{ number_format($total_price, 2) }}
                            </p>
                            @error('total_price')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Grand Total</p>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                ${{ number_format($grand_total, 2) }}
                            </p>
                            @error('grand_total')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="flex justify-end space-x-3 bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-md transition-colors">
                        Cancel
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                        class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="save">
                            {{ $editMode ? 'Update Sale' : 'Create Sale' }}
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.48 0 0 6.48 0 12h4z">
                                </path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-modal>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('message.processed', (message, component) => {
            // Handle delayed dropdown hiding
            document.addEventListener('hide-dropdown-delayed', function () {
                setTimeout(() => {
                    @this.set('show_dropdown', false);
                }, 200);
            });
        });
    });
</script> --}}