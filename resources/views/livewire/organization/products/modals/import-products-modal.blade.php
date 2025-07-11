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
                        <x-input-label for="supplier" :value="__('Supplier')" />
                        <select id="supplier" name="supplier_id"
                            class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">{{ __('Select Supplier') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
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