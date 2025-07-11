<div>
    <div>
        @if (!$viewPurchaseOrder)
            <div class="py-1">
                <div class="max-w-5xl mx-auto">
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-3">
                        <section class="w-full">
                            <header
                                class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Purchase orders') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Manage Purchase orders related to your organization') }}
                                    </p>
                                </div>
                                <!-- location dropdown -->
                                <div class="flex items-center justify-center gap-3">

                                    @php
                                        $user = auth()->user();
                                        $role = $user->role;
                                    @endphp
                                    @if (!$role?->hasPermission('all_purchase') && $user->role_id > 2)

                                    @else
                                        <div class="dark:text-gray-100">Location:</div>
                                        <select wire:model.live="selectedLocation"
                                            class="dark:bg-gray-800 dark:text-gray-100 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </header>
                        </section>
                    </div>
                    <div class="relative w-full mb-2">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="purchaseOrderSearch"
                            placeholder="Search Purchase Order number or Supplier..."
                            wire:model.live.debounce.300ms="searchPurchaseOrder" autocomplete="off"
                            class="pl-10 w-full px-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-primary-md focus:border-primary-md dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                        @if($searchPurchaseOrder)
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button wire:click="clearSearch" type="button"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                {{-- <livewire:user.purchase.purchase-order-list-component /> --}}
            </div>
            <div>
                <div class="max-w-5xl mx-auto">
                    @if ($purchaseOrderList->count() > 0)
                        @foreach($purchaseOrderList->sortByDesc('created_at') as $order)
                            @include('livewire.user.purchase.purchase-order-card')
                        @endforeach
                    @else
                        <div
                            class="flex flex-col items-center w-full px-4 py-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-800">
                            @if (!empty($searchPurchaseOrder))
                                {{-- Search Filter Active – Show Illustration --}}
                                @include('livewire.user.purchase.empty-state-with-filter', ['clearSearchMethod' => 'clearSearch'])
                            @else
                                {{-- No Purchase Orders Yet --}}
                                <div class="text-center">
                                    <p class="text-gray-700 dark:text-gray-300 font-medium">No purchase orders available</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                                        No purchase orders have been created yet or match your current filters.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

        @else
                @include('livewire.user.purchase.view-purchase-order')
            @endif
        </div>
        <!-- Modal for receiving products -->
        @include('livewire.user.purchase.modals.receive-product-model')
        @include('livewire.admin.purchase.modals.preview-modal')
    </div>