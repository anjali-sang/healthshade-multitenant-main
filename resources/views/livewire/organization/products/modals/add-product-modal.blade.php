<x-modal name="add-product-modal" width="w-100" height="h-auto" maxWidth="6xl">
    <div class="bg-white dark:bg-gray-800">
        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Add Product') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Create a new product and your product will be added once approved.') }}
            </p>
        </div>

        <form wire:submit.prevent="createProduct" class="max-h-[80vh] overflow-y-auto">
            <div class="p-8 space-y-8">

                <!-- Basic Information Section -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('Basic Information') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="product_code" :value="__('Product Code')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                           
                            <x-text-input id="product_code" wire:model="product_code" type="text" 
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="Enter product code" required />
                            @error('product_code')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="product_name" :value="__('Product Name')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                           
                            <x-text-input id="product_name" wire:model="product_name" type="text" 
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="Enter product name" required />
                            @error('product_name')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="product_supplier_id" :value="__('Supplier')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                           
                            {{-- <select id="product_supplier_id" wire:model="product_supplier_id"
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                required>
                                <option value="">{{ __('Select Supplier') }}</option>
                                @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select> --}}

                            <x-text-input id="product_supplier_id" wire:model="product_supplier_id" type="text"
        class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
        placeholder="Enter supplier name" required />

                            @error('product_supplier_id')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative">
                            <x-input-label for="brand" :value="__('Manufacturer')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <input 
                                type="text" 
                                wire:model.live="brand_search"
                                wire:focus="showDropdown"
                                wire:blur="hideDropdown"
                                id="brand"
                                placeholder="{{ __('Search Manufacturer...') }}"
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                autocomplete="off"
                            />
    
    <!-- Hidden input to store the actual brand_id -->
    <input type="hidden" wire:model="brand_id" />
    
    <!-- Dropdown List -->
    @if($show_dropdown && count($filtered_brands) > 0)
        <div class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
            @foreach($filtered_brands as $brand)
                <div 
                    wire:click="selectBrand({{ $brand->id }}, '{{ $brand->brand_name }}')"
                    class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 last:border-b-0"
                >
                    {{ $brand->brand_name }}
                </div>
            @endforeach
        </div>
    @endif
    
    @error("brand_id")
        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
    @enderror
</div>


                        <div>
                            <x-input-label for="manufacture_code" :value="__('Manufacturing Code')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                           
                            <x-text-input wire:model="manufacture_code" type="text" 
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="Enter manufacturing code" required />
                            @error('manufacture_code')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Product Category')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <select wire:model="category_id" id="category" 
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @error("category_id")
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="cost" :value="__('Product Cost')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                           
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">{{ $organization?->currency }}</span>
                                </div>
                                <x-text-input wire:model="cost" type="text" 
                                    class="block w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="0.00" required />
                            </div>
                            @error('cost')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Units Configuration Section -->
                <div class="bg-blue-50 dark:bg-gray-900 rounded-xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 002 2z"/>
                            </svg>
                            {{ __('Units Configuration') }}
                        </h3>
                        @if ($baseUnit)
                            <button type="button" wire:click="addUnit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ __('Add more unit') }}
                            </button>
                        @endif
                    </div>

                    @error('units')
                        <div class="text-red-500 text-sm mb-4">{{ $message }}</div>
                    @enderror

                    <!-- Base Unit Selection -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4">
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <x-input-label for="units" :value="__('Base Unit')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                               
                                <select wire:model.live="baseUnit"
                                    class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">{{ __('Select Base Unit') }}</option>
                                    @foreach ($availableUnits as $availableUnit)
                                        <option value="{{ $availableUnit->id }}">
                                            {{ $availableUnit->unit_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('baseUnit')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Units -->
                    @foreach ($units as $index => $unit)
                        @if ($index > 0)
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4 border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-4">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Unit') }}
                                        </label>
                                        <select wire:model.live="units.{{ $index }}.unit_id"
                                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                            required>
                                            <option value="">{{ __('Select Unit') }}</option>
                                            @foreach ($availableUnits as $availableUnit)
                                                <option value="{{ $availableUnit->id }}"
                                                    @if ($availableUnit->id == $baseUnit || collect($units)->pluck('unit_id')->contains($availableUnit->id)) disabled @endif>
                                                    {{ $availableUnit->unit_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("units.{$index}.unit_id")
                                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Conversion Factor') }}
                                        </label>
                                        <input type="number"
                                            wire:model="units.{{ $index }}.conversion_factor"
                                            step="0.01" placeholder="1.00"
                                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        @error("units.{$index}.conversion_factor")
                                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="button" wire:click="removeUnit({{ $index }})"
                                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Product Details Section -->
                <div class="bg-purple-50 dark:bg-gray-900 rounded-xl p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ __('Product Details') }}
                    </h3>

                    <div class="space-y-6">
                        <!-- Description -->
                        <div>
                            <x-input-label for="product_description" :value="__('Description')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                           
                            <textarea id="product_description" wire:model="product_description" rows="4"
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 resize-none" 
                                placeholder="Enter detailed product description..." required></textarea>
                            @error('product_description')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Product Images -->
                        <div>
                            <x-input-label for="images" :value="__('Product Images')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                           
                            <div class="mt-2 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                <input type="file" id="images" wire:model="images" multiple accept="image/jpeg, image/png, image/webp"

                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            @error('images.*')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror

                            <!-- Image Preview -->
                            @if ($images)
                                <div class="mt-4 grid grid-cols-3 gap-4">
                                    @foreach ($images as $image)
                                        <div class="relative">
                                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-24 object-cover rounded-lg">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Batch & Expiry -->
                        <div class="flex items-center gap-3 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <input class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" 
                                id="isBatch" type="checkbox" wire:model="isBatch">
                            <x-input-label for="isBatch" :value="__('This product has batch and expiry date?')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            @error('isBatch')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dimensions & Weight Section -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                        {{ __('Dimensions & Weight') }}
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <x-input-label for="weight" :value="__('Weight (lbs)')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <x-text-input id="weight" wire:model="weight" type="number" step="0.01" 
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="0.00" />
                            @error('weight')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="length" :value="__('Length (cm)')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <x-text-input id="length" wire:model="length" type="number" step="0.01" 
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="0.00" />
                            @error('length')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="width" :value="__('Width (cm)')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <x-text-input id="width" wire:model="width" type="number" step="0.01" 
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="0.00" />
                            @error('width')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="height" :value="__('Height (cm)')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <x-text-input id="height" wire:model="height" type="number" step="0.01" 
                                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                placeholder="0.00" />
                            @error('height')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <!-- Form Actions -->
<div class="px-4 sm:px-8 py-6 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 sm:gap-4">
                <x-secondary-button x-on:click="$dispatch('close-modal', 'add-product-modal')" 
                    class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    {{ __('Cancel') }}
                </x-secondary-button>
                
                <x-primary-button class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors" 
                    x-data="{ loading: false }" 
                    x-on:click="loading = true; $wire.createProduct().then(() => loading = false)" 
                    x-bind:disabled="loading">
                    <span x-show="!loading">{{ __('Create Product') }}</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                        {{ __('Creating...') }}
                    </span>
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('message.processed', (message, component) => {
            // Handle delayed dropdown hiding
            document.addEventListener('hide-dropdown-delayed', function() {
                setTimeout(() => {
                    @this.set('show_dropdown', false);
                }, 200);
            });
        });
    });
</script>