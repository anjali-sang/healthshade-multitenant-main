<div>
    <section class="w-full border-b-2 pb-4 mb-6">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Batch (LOT#) Inventory') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('View information like lot number and expiry date here.') }}
                </p>
            </div>
            <!-- location dropdown -->
            <div class="flex items-center justify-center gap-3">

                @php
                    $user = auth()->user();
                    $role = $user->role;
                @endphp
                @if (!$role?->hasPermission('all_inventory') && $user->role_id > 2)

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
    <div class="text-xs">
        <livewire:tables.organization.inventory.batch-inventory-table />
    </div>
</div>