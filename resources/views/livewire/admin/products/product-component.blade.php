<div>
    <div class="py-6">
        <div class="max-w-screen-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header
                    class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3 border-b pb-6 mb-3">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Manage Products') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Review and manage Products required for Organizations.') }}
                        </p>
                    </div>
                    <div>
                        <x-secondary-button class="min-w-36 flex justify-center items-center" x-data="{ loading: false }"
                            x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'import-products-modal'); loading = false }, 1000)"
                            x-bind:disabled="loading">
                            <!-- Button Text -->
                            <span x-show="!loading">{{ __('Import Products') }}</span>
                            <!-- Loader (Spinner) -->
                            <span x-show="loading" class="flex justify-center items-center w-full">
                                <svg class="animate-spin h-4 w-4 text-primary-md" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z">
                                    </path>
                                </svg>
                            </span>
                        </x-secondary-button>
                        <x-primary-button class="min-w-36 flex justify-center items-center" x-data="{ loading: false }"
                            x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'add-product-modal'); loading = false }, 1000)"
                            x-bind:disabled="loading">
                            <!-- Button Text -->
                            <span x-show="!loading">{{ __('+ Add Product') }}</span>
                            <!-- Loader (Spinner) -->
                            <span x-show="loading" class="flex justify-center items-center w-full">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z">
                                    </path>
                                </svg>
                            </span>
                        </x-primary-button>
                    </div>
                </header>
                <div class="text-xs">
                    <livewire:tables.admin.products-list />
                </div>
            </div>
        </div>
    </div>

    <x-modal name="import-products-modal" width="w-100" height="h-auto" maxWidth="4xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Import Products') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Upload a CSV file to import multiple products at once. Only the base unit will be imported.') }}
            </p>
        </header>
        <form action="{{ route('import.products') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6 p-3">
                <div class="border-b border-gray-900/10 pb-6">
                    <div class="grid grid-cols-6">
                        <!-- Supplier Selection -->
                        <div class="col-span-3 p-3">
                            {{-- <x-input-label for="supplier" :value="__('Supplier')" /> --}}
                            {{-- <select id="supplier" name="supplier_id"
                                class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">{{ __('Select Supplier') }}</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select> --}}

                            <x-text-input id="product_supplier_id" wire:model="product_supplier_id" type="text"
        class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
        placeholder="Enter supplier name" required />
                        </div>
                        <!-- CSV Template Example -->
                        <div class="col-span-3 p-3">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('CSV Format') }}
                            </h3>
                            <div class="mt-2">
                                <x-secondary-button type="button" wire:click="downloadSampleCsv">
                                    {{ __('Download Sample CSV') }}
                                </x-secondary-button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CSV File Upload -->
                <div class="mt-4">
                    <x-input-label for="csv_file" :value="__('*CSV File')" />
                    <input type="file" name="csv_file" id="csv_file"
                        class="mt-1 block w-full border rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        accept=".csv">
                    @error('csvFile')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6 p-3" x-data="{ loading: false }">
                <x-primary-button typr="submit" class="min-w-24 flex justify-center items-center">Import
                </x-primary-button>
                <x-secondary-button
                    x-on:click="$dispatch('close-modal', 'import-products-modal')">{{ __('Cancel') }}</x-secondary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="add-product-modal" width="w-100" height="h-auto" maxWidth="4xl">
        <header class="p-3">
            @if (auth()->user()->role_id == '1')
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Add New Product') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Create a new product for your client. Ensure that all your details are accurate before proceeding.') }}
                </p>
            @else
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Request a New Product') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Create a new product and your product will be added once approved.') }}
                </p>
            @endif
        </header>
        <form wire:submit.prevent="createProduct">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <x-input-label for="product_code" :value="__('*Product Code')" />
                            <x-text-input id="product_code" wire:model="product_code" type="text"
                                class="mt-1 block w-full" required />
                            @error('product_code')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-4">
                            <x-input-label for="product_name" :value="__('*Product Name')" />
                            <x-text-input id="product_name" wire:model="product_name" type="text"
                                class="mt-1 block w-full" required />
                            @error('product_name')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="product_supplier_id" :value="__('*Supplier')" />
                            {{-- <select id="product_supplier_id" wire:model="product_supplier_id"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select> --}}

                            <x-text-input id="product_supplier_id" wire:model="product_supplier_id" type="text"
        class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
        placeholder="Enter supplier name" required />

                            @error('product_supplier_id')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="manufacture_code" :value="__('*Mfc Code')" />
                            <x-text-input wire:model="manufacture_code" type="text" class="mt-1 block w-full"
                                required />
                            @error('manufacture_code')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="organization_id" :value="__('*Organization')" />
                            <select id="organization_id" wire:model="organization_id"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @error('organization_id')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="cost" :value="__('*Product Cost')" />
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">{{ $organization?->currency }}</span>
                                </div>
                                <x-text-input wire:model="cost" type="text" class="mt-1 block w-full pl-8" required />
                            </div>
                            @error('cost')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Units Section --}}
                        <div class="sm:col-span-6 bg-slate-100 dark:bg-gray-700 rounded p-3">
                            <div class="flex justify-between items-center">
                                <x-input-label for="unit" :value="__('Units')" />
                            </div>
                            @error('units')
                                <span class="text-red-500 text-sm block">{{ $message }}</span>
                            @enderror
                            {{-- Dynamic Units --}}
                            <div class="sm:col-span-6">
                                <!-- Base Unit Selection  -->
                                <div class="flex items-center gap-4 p-4">
                                    <x-input-label for="units" :value="__('*Base Unit')" />
                                    <select wire:model.live="baseUnit"
                                        class="bg-white border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Select Base Unit</option>
                                        @foreach ($availableUnits as $availableUnit)
                                            <option value="{{ $availableUnit->id }}">
                                                {{ $availableUnit->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- Add Unit Button -->
                                    @if ($baseUnit)
                                        <button type="button" wire:click="addUnit"
                                            class="px-1 py-1 bg-green-500 text-white rounded-full hover:bg-green-600">
                                            <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5" />
                                            </svg>
                                        </button>
                                    @endif

                                    @error('baseUnit')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Additional Units -->
                                @foreach ($units as $index => $unit)
                                    @if ($index > 0)
                                        {{-- Skip base unit as it's already shown above --}}
                                        <div class="flex items-center gap-4 p-4 rounded dark:text-gray-300">
                                             <!-- Display Base Unit -->
                                             <div class="flex-1">
                                                <span class="block text-gray-900 dark:text-gray-100">
                                                    {{ $baseUnit ? '1 ' . $availableUnits->firstWhere('id', $baseUnit)?->unit_name : 'No Unit Selected' }}
                                                </span>
                                            </div>
                                            <!-- Operator -->
                                            <div class="w-32">
                                                <select wire:model="units.{{ $index }}.operator"
                                                    class="bg-white w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                    <option value="multiply">×</option>
                                                    <option value="divide">÷</option>
                                                </select>
                                            </div>
                                            <!-- Conversion Factor -->
                                            <div class="w-32">
                                                <input type="number"
                                                    wire:model="units.{{ $index }}.conversion_factor"
                                                    step="0.01" placeholder="Value"
                                                    class="w-full rounded-md border-gray-300">
                                                @error("units.{$index}.conversion_factor")
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <!-- Equals Sign -->
                                            <div class="text-xl">=</div>
                                           
                                            <!-- Unit Selection -->
                                            <div class="flex-1">
                                                <select wire:model.live="units.{{ $index }}.unit_id"
                                                    class="bg-white w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                    required>
                                                    <option value="">Select Unit</option>
                                                    @foreach ($availableUnits as $availableUnit)
                                                        <option value="{{ $availableUnit->id }}"
                                                            @if ($availableUnit->id == $baseUnit || collect($units)->pluck('unit_id')->contains($availableUnit->id)) disabled @endif>
                                                            {{ $availableUnit->unit_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("units.{$index}.unit_id")
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <!-- Remove Button -->
                                            <button type="button" wire:click="removeUnit({{ $index }})"
                                                class="p-2 text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <x-input-label for="product_description" :value="__('*Description')" />
                            <x-text-input id="product_description" wire:model="product_description" type="text"
                                class="mt-1 block w-full" required />
                            @error('product_description')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-6">
                            <x-input-label for="images" :value="__('*Product Images')" />
                            <input type="file" id="images" wire:model="images" multiple
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('images.*')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <!-- Image Preview -->
                            <div class="mt-4 grid grid-cols-3 gap-2">
                                @if ($images)
                                    @foreach ($images as $image)
                                        <img src="{{ $image->temporaryUrl() }}"
                                            class="w-24 h-24 object-cover rounded">
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6" x-data="{ loading: false }">
                <x-primary-button class="min-w-24 flex justify-center items-center" x-data="{ loading: false }"
                    x-on:click="loading = true; $wire.createProduct().then(() => loading = false)"
                    x-bind:disabled="loading">
                    <!-- Button Text -->
                    <span x-show="!loading">{{ __('Create') }}</span>
                    <!-- Loader (Spinner) -->
                    <span x-show="loading" class="absolute flex items-center justify-center">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </x-primary-button>
                <x-secondary-button
                    x-on:click="$dispatch('close-modal', 'add-product-modal')">{{ __('Cancel') }}</x-secondary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="edit-product-modal" width="w-100" height="h-auto" maxWidth="4xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit Product') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Update the details of the product. Ensure that all your changes are accurate before saving.') }}
            </p>
        </header>
        <form wire:submit.prevent="updateProduct">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <x-input-label for="edit_product_code" :value="__('*Product Code')" />
                            <x-text-input id="edit_product_code" wire:model="product_code" type="text"
                                class="mt-1 block w-full" required />
                            @error('product_code')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-4">
                            <x-input-label for="edit_product_name" :value="__('*Product Name')" />
                            <x-text-input id="edit_product_name" wire:model="product_name" type="text"
                                class="mt-1 block w-full" required />
                            @error('product_name')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label for="edit_product_supplier_id" :value="__('*Supplier')" />
                            {{-- <select id="edit_product_supplier_id" wire:model="product_supplier_id"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select> --}}
                            <x-text-input id="product_supplier_id" wire:model="product_supplier_id" type="text"
        class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
        placeholder="Enter supplier name" required />
                            @error('product_supplier_id')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="edit_manufacture_code" :value="__('*Mfc Code')" />
                            <x-text-input wire:model="manufacture_code" type="text" class="mt-1 block w-full"
                                required />
                            @error('manufacture_code')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Units Section --}}
                        <div class="sm:col-span-6 bg-slate-100 dark:bg-gray-700 rounded p-3">
                            <div class="flex justify-between items-center">
                                <x-input-label for="edit_units" :value="__('Units')" />
                            </div>
                            @error('editUnits')
                                <span class="text-red-500 text-sm block">{{ $message }}</span>
                            @enderror
                            {{-- Dynamic Units --}}
                            <div class="sm:col-span-6">
                                <!-- Base Unit Selection  -->
                                <div class="flex items-center gap-4 p-4">
                                    <x-input-label for="edit_base_unit" :value="__('*Base Unit')" />
                                    <select wire:model.live="baseUnit"
                                        class="bg-white border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Select Base Unit</option>
                                        @foreach ($availableUnits as $availableUnit)
                                            <option value="{{ $availableUnit->id }}"
                                                {{ $availableUnit->id == $baseUnit ? 'selected' : '' }}>
                                                {{ $availableUnit->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- Add Unit Button -->
                                    @if ($baseUnit)
                                        <button type="button" wire:click="addEditUnit"
                                            class="px-1 py-1 bg-green-500 text-white rounded-full hover:bg-green-600">
                                            <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5" />
                                            </svg>
                                        </button>
                                    @endif
                                    @error('editBaseUnit')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Additional Units -->
                                @foreach ($units as $index => $unit)
                                    @if ($index >= 0)
                                        <div class="flex items-center gap-4 p-4 rounded dark:text-gray-300">
                                            <!-- Unit Selection -->
                                            <div class="flex-1">
                                                <select wire:model="units.{{ $index }}.unit_id"
                                                    class="bg-white w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                    required>
                                                    <option value="">Select Unit</option>
                                                    @foreach ($availableUnits as $availableUnit)
                                                        <option value="{{ $availableUnit->id }}"
                                                            @if ($availableUnit->id == $baseUnit || collect($units)->pluck('unit_id')->contains($availableUnit->id)) disabled @endif>
                                                            {{ $availableUnit->unit_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("units.{$index}.unit_id")
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <!-- Operator -->
                                            <div class="w-32">
                                                <select wire:model="units.{{ $index }}.operator"
                                                    class="bg-white w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                    <option value="multiply">×</option>
                                                    <option value="divide">÷</option>
                                                </select>
                                            </div>
                                            <!-- Conversion Factor -->
                                            <div class="w-32">
                                                <input type="number"
                                                    wire:model="units.{{ $index }}.conversion_factor"
                                                    step="0.01" placeholder="Value"
                                                    class="w-full rounded-md border-gray-300">
                                                @error("units.{$index}.conversion_factor")
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <!-- Equals Sign -->
                                            <div class="text-xl">=</div>
                                            <!-- Display Base Unit -->
                                            <div class="flex-1">
                                                <span class="block text-gray-900 dark:text-gray-100">
                                                    {{ $baseUnit ? '1 ' . $availableUnits->firstWhere('id', $baseUnit)?->unit_name : 'No Unit Selected' }}
                                                </span>
                                            </div>
                                            <!-- Remove Button -->
                                            <button type="button" wire:click="removeEditUnit({{ $index }})"
                                                class="p-2 text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <x-input-label for="edit_product_description" :value="__('*Description')" />
                            <x-text-input id="edit_product_description" wire:model="product_description"
                                type="text" class="mt-1 block w-full" required />
                            @error('product_description')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Preselected Image Section -->
                        <div class="sm:col-span-6">
                            <x-input-label for="preselected_image" :value="__('Preselected Image')" />
                            @if ($existingImage)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $existingImage) }}" alt="Preselected Image"
                                        class="w-24 h-24 object-cover rounded">
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">No image selected.</p>
                            @endif
                        </div>
                        <!-- Image Upload Section -->
                        <div class="sm:col-span-6">
                            <x-input-label for="images" :value="__('*Product Images')" />
                            <div class="mt-1 flex items-center">
                                <input type="file" id="images" wire:model="images" multiple
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">

                                </span>
                            </div>
                            @error('images.*')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <!-- Image Preview -->
                            <div class="mt-4 grid grid-cols-3 gap-2">
                                @if ($images)
                                    @foreach ($images as $image)
                                        <img src="{{ $image->temporaryUrl() }}"
                                            class="w-24 h-24 object-cover rounded">
                                    @endforeach
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <footer class="flex items-center justify-end gap-4">
                <!-- Add Update Button -->
                <x-primary-button
                    class="min-w-24 flex justify-center items-center text-sm/6 font-semibold text-gray-900"
                    x-data="{ loading: false }"
                    x-on:click="loading = true; $wire.updateProduct().then(() => { loading = false; })"
                    x-bind:disabled="loading">
                    <!-- Button Text -->
                    <span x-show="!loading">{{ __('Update') }}</span>

                    <!-- Loader (Spinner) -->
                    <span x-show="loading" class="flex justify-center items-center w-full">
                        <svg class="animate-spin h-4 w-4 text-gray-900" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </x-primary-button>

                <!-- Add Delete Button -->
                <x-danger-button wire:click="deleteProduct"
                    class="inline-flex items-center text-sm/6 font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors">
                    <svg class="h-4 w-4 mr-1 text-white hover:text-red-100" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                    </svg>
                    {{ __('Delete') }}
                </x-danger-button>


                <x-secondary-button
                    x-on:click="$dispatch('close-modal', 'edit-product-modal')">{{ __('Cancel') }}</x-secondary-button>
            </footer>
        </form>
    </x-modal>
    <!-- Notifications Container -->
    <div class="fixed top-24 right-4 z-50 space-y-2">
        @foreach ($notifications as $notification)
            <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }" x-init="setTimeout(() => {
                show = false;
                $wire.removeNotification('{{ $notification['id'] }}');
            }, 3000)"
                x-show="show" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-full"
                class="{{ $notification['type'] === 'success' ? 'border-green-800 text-green-800  bg-green-300' : 'bg-red-300 border-red-800 text-red-800' }} border-l-4 x-6 py-6 px-4  shadow-lg bg-white dark:bg-gray-700">
                <p>{{ $notification['message'] }}</p>
            </div>
        @endforeach
    </div>
</div>
