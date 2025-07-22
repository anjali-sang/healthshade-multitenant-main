<div class=" px-1 lg:px-4 sm:px-6 lg:px-8">
    <section class="w-full border-b-2 pb-4 mb-6">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-4 md:gap-6">
            <!-- Heading and description -->
            <div class="flex-1">
                <h2 class="text-base sm:text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Manage Inventory') }}
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Manage products inventory and update the alert and par quantity.') }}
                </p>
            </div>

            <!-- Location Dropdown -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 w-full md:w-auto">
                @php
                    $user = auth()->user();
                    $role = $user->role;
                @endphp

                @if (!$role?->hasPermission('all_inventory') && $user->role_id > 2)
                    <!-- Restricted users see nothing -->
                @else
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-100">Location:</label>
                    <select wire:model.live="selectedLocation"
                        class="mt-1 block w-full sm:w-auto text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </header>
    </section>

    <div class="text-xs sm:text-sm">
        <livewire:tables.organization.inventory.inventory-list />
    </div>

    @include('livewire.organization.inventory.modals.add-product-to-cart')
</div>
