{{-- <div class="p-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <!-- Header -->
        <div
            class="flex justify-between items-start md:items-center gap-4 px-6 pt-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Sales') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Create and manage all sales related information for your organization.') }}
                </p>
            </div>

            <x-primary-button class="min-w-36 flex justify-center items-center" x-data="{ loading: false }"
                @click="loading = true; $wire.call('openSaleModal').then(() => loading = false)"
                x-bind:disabled="loading">
                <!-- Button Text -->
                <span x-show="!loading">{{ __('+ Create Sale') }}</span>
                <!-- Loader -->
                <span x-show="loading" class="flex items-center">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.48 0 0 6.48 0 12h4z" />
                    </svg>
                    <span class="ml-2">{{ __('Loading...') }}</span>
                </span>
            </x-primary-button>
        </div>


        <!-- sales List -->
        <div class="px-6 py-4">
            <!-- Add your sales list/table here -->
            <div class="text-xs">
                <livewire:tables.medicalrep.sales-list />
            </div>
        </div>
        @include('livewire.medical-rep.modals.sales-modal')
        @include('livewire.medical-rep.modals.confirmation-modal')
    </div>
    <div>
        <!-- Shipping Confirmation Modal -->
        @if($showShippingModal)
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                x-data="{ show: @entangle('showShippingModal') }" x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">

                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-shipping-fast text-blue-600 mr-2"></i>
                            Create UPS Shipment
                        </h3>
                        <button wire:click="closeModal('shipping_confirmation_modal')"
                            class="text-gray-400 hover:text-gray-600 text-2xl">
                            &times;
                        </button>
                    </div>

                    <!-- Sale Information -->
                    @if($sale)
                        <div class="mt-4 space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Sale Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-600">Sale Number:</span>
                                        <span class="ml-2">{{ $sale->sales_number }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600">Total Price:</span>
                                        <span
                                            class="ml-2 font-semibold text-green-600">${{ number_format($sale->total_price, 2) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600">Total Quantity:</span>
                                        <span class="ml-2">{{ $sale->total_qty }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600">Status:</span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                        @if($sale->status === 'completed') bg-green-100 text-green-800
                                        @elseif($sale->status === 'shipped') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Information -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Shipping Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-600">From:</span>
                                        <div class="ml-2 text-gray-800">
                                            {{ $sale->organization->name ?? 'N/A' }}<br>
                                            {{ $sale->organization->address ?? 'Address not available' }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600">To:</span>
                                        <div class="ml-2 text-gray-800">
                                            {{ $sale->receiverOrganization->name ?? 'N/A' }}<br>
                                            {{ $sale->receiverOrganization->address ?? 'Address not available' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Items Summary -->
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Items to Ship</h4>
                                <div class="space-y-2">
                                    @foreach($sale->saleItems as $item)
                                        <div class="flex justify-between items-center text-sm">
                                            <span>{{ $item->product_name }} ({{ $item->product_code }})</span>
                                            <span class="font-medium">Qty: {{ $item->quantity }} |
                                                ${{ number_format($item->total, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Messages -->
                    @if($errorMessage)
                        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $errorMessage }}
                        </div>
                    @endif

                    @if($successMessage)
                        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ $successMessage }}
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-end pt-6 border-t mt-6 space-x-4">
                        <button wire:click="closeModal('shipping_confirmation_modal')"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>

                        @if(!$shipment)
                            <button wire:click="fetchSale" wire:loading.attr="disabled"
                                class="px-6 py-2 bg-blue-600 text-white text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="fetchSale">
                                    <i class="fas fa-shipping-fast mr-2"></i>
                                    Create UPS Shipment
                                </span>
                                <span wire:loading wire:target="fetchSale">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Creating Shipment...
                                </span>
                            </button>
                        @else
                            <div class="text-green-600 font-medium">
                                <i class="fas fa-check-circle mr-2"></i>
                                Shipment Already Created
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Tracking Modal -->
        @if($showTrackingModal)
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                x-data="{ show: @entangle('showTrackingModal') }" x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">

                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-search-location text-purple-600 mr-2"></i>
                            Track Shipment: {{ $shipment->tracking_number ?? '' }}
                        </h3>
                        <button wire:click="closeModal('tracking_modal')"
                            class="text-gray-400 hover:text-gray-600 text-2xl">
                            &times;
                        </button>
                    </div>

                    <!-- Tracking Information -->
                    <div class="mt-4">
                        @if(count($trackingInfo) > 0)
                            <div class="space-y-4">
                                @foreach($trackingInfo as $info)
                                    <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $info['status'] }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">{{ $info['description'] }}</p>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ $info['location'] }}
                                                </p>
                                            </div>
                                            <div class="text-right text-sm text-gray-500">
                                                <div>{{ $info['date'] }}</div>
                                                <div>{{ $info['time'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-info-circle text-gray-400 text-3xl mb-4"></i>
                                <p class="text-gray-500">No tracking information available yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end pt-6 border-t mt-6">
                        <button wire:click="closeModal('tracking_modal')"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Shipment Summary Card (if shipment exists) -->
        @if($shipment)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-box text-blue-600 mr-2"></i>
                        Shipment Information
                    </h3>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($shipment->status === 'shipped') bg-blue-100 text-blue-800
                    @elseif($shipment->status === 'delivered') bg-green-100 text-green-800
                    @elseif($shipment->status === 'in_transit') bg-purple-100 text-purple-800
                    @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Tracking Number:</span>
                        <p class="text-lg font-mono text-gray-900">{{ $shipment->tracking_number }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Carrier:</span>
                        <p class="text-gray-900">{{ $shipment->carrier }} {{ $shipment->service_type }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Shipping Cost:</span>
                        <p class="text-gray-900 font-semibold">${{ number_format($shipment->cost, 2) }}</p>
                    </div>
                </div>

                <div class="flex space-x-4">
                    <button wire:click="trackShipment" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50">
                        <span wire:loading.remove wire:target="trackShipment">
                            <i class="fas fa-search-location mr-2"></i>
                            Track Package
                        </span>
                        <span wire:loading wire:target="trackShipment">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Tracking...
                        </span>
                    </button>

                    @if($shipment->label_url)
                        <button wire:click="downloadLabel"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-download mr-2"></i>
                            Download Label
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div> --}}