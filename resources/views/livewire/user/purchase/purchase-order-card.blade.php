<div class="dark:bg-gray-800 dark:border-gray-700 bg-white rounded-lg border border-gray-200 p-5 mb-2 order-item" data-order="{{ $order->purchase_oder_number }}">

    <!-- Header: Order Number and Status -->
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
        <div class="space-y-2">
            <p class="text-sm sm:text-base dark:text-gray-200 text-gray-700">
                <span class="font-medium">Purchase Order:</span>
                <span class="font-semibold text-black dark:text-gray-100">
                    {{ $order->merge_id ?? $order->purchase_oder_number }}
                </span>
                <span class="ml-4 inline-block py-0.5 px-1.5 text-xs rounded-full border-2
                    @if ($order->status == 'ordered') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 border-blue-800
                    @elseif ($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 border-yellow-800
                    @elseif ($order->status == 'partial') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300 border-orange-800
                    @elseif ($order->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 border-green-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            @include('livewire.user.purchase.partials.invoice-buttons')
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-2">
            <x-primary-button
                wire:click="fetchPoModal({{ $order->id }})"
                class="relative inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-primary-md dark:bg-primary-md border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dk focus:bg-primary-dk active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 whitespace-nowrap">
                <span x-data="{ loading: false }" x-on:click="loading = true; setTimeout(() => loading = false, 1000)" class="flex items-center justify-center w-full">
                    <span :class="{ 'invisible': loading }">View Order</span>
                    <span x-show="loading" class="absolute flex items-center justify-center">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </span>
            </x-primary-button>

            <x-primary-button
                wire:click="receiveProduct({{ $order->id }})"
                class="relative inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 whitespace-nowrap">
                <span x-data="{ loading: false }" x-on:click="loading = true; setTimeout(() => loading = false, 1000)" class="flex items-center justify-center w-full">
                    <span :class="{ 'invisible': loading }">Receive Order</span>
                    <span x-show="loading" class="absolute flex items-center justify-center">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </span>
            </x-primary-button>
        </div>
    </div>

    <!-- Order Info Rows -->
    <div class="flex flex-col md:flex-row md:flex-wrap gap-3 md:gap-6 text-sm sm:text-base">
        <div class="flex items-center gap-x-2">
            <span class="text-gray-600 dark:text-gray-300">Order date:</span>
            <span class="font-medium dark:text-gray-100">
                {{ date(session('date_format', 'd F Y'), strtotime($order->created_at)) }}
            </span>
        </div>
        <div class="flex items-center gap-x-2">
            <span class="text-gray-600 dark:text-gray-300">Email:</span>
            <span class="font-medium dark:text-gray-100">
                {{ $order->purchaseSupplier->supplier_email ?? 'N/A' }}
            </span>
        </div>
        <div class="flex items-center gap-x-2">
            <span class="text-gray-600 dark:text-gray-300">Supplier:</span>
            <span class="font-medium dark:text-gray-100">
                {{ $order->purchaseSupplier->supplier_name ?? 'N/A' }}
            </span>
        </div>
        <div class="flex items-center gap-x-2">
            <span class="text-gray-600 dark:text-gray-300">Location:</span>
            <span class="font-medium dark:text-gray-100">
                {{ $order->purchaseLocation->name ?? 'N/A' }}
            </span>
        </div>
    </div>

    <!-- Tracking / Note -->
    @if (!empty($order->tracking_link))
        <div class="mt-4 dark:bg-gray-800 bg-green-50 p-3 rounded-md border border-green-100">
            <p class="flex items-center text-green-700 dark:text-green-200 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $order->note }} 
                <a href="{{ $order->tracking_link }}" target="_blank" class="ml-2 text-blue-500 dark:text-blue-400 underline">
                    {{ __('Track order') }}
                </a>
            </p>
        </div>
    @else
        <div class="mt-4 dark:bg-gray-800 bg-orange-50 p-3 rounded-md border border-orange-100">
            <p class="flex items-center text-orange-800 dark:text-orange-200 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $order->note ?? 'No update available' }}
            </p>
        </div>
    @endif
</div>
