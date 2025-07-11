<div class="mx-3 sm:mx-6 my-3 px-4 sm:px-6 py-4 bg-white dark:bg-gray-800 rounded-lg border">
    <div class="bg-white dark:bg-gray-800 pb-3">
        <!-- Tab Navigation - Mobile Responsive -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center dark:border-gray-700 gap-4 sm:gap-6 px-2">
            <nav class="flex flex-col sm:flex-row sm:space-x-8 space-y-2 sm:space-y-0 w-full sm:w-auto">
                <button id="picking-btn" wire:click="switchTab('picking')"
                    class="tab-btn flex items-center justify-center px-3 py-2 text-sm sm:text-base text-gray-600 @if($isPicking) text-primary-md font-medium border-primary-md @endif border-b-2 hover:border-primary-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 8h10M9 12h10M9 16h10M5 8H5m0 4h.01m0 4H5" />
                    </svg>
                    Picking
                </button>
                <button id="batch-btn" wire:click="switchTab('batch')"
                    class="tab-btn flex items-center justify-center px-3 py-2 text-sm sm:text-base text-gray-600 @if(!$isPicking) text-primary-md font-medium border-primary-md @endif border-b-2 hover:border-primary-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 12v1h4v-1m4 7H6a1 1 0 0 1-1-1V9h14v9a1 1 0 0 1-1 1ZM4 5h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                    </svg>
                    Batch (LOT#) Picking
                </button>
            </nav>
            
            @php
                $user = auth()->user();
                $role = $user->role;
            @endphp
            @if ($role?->hasPermission('all_picking') || $user->role_id <= 2)
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <div class="text-sm dark:text-gray-100">Location</div>
                    <select wire:model.live="selectedLocation"
                        class="mt-1 block w-full sm:w-auto dark:bg-gray-800 dark:text-gray-100 pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    <div class="pt-4 text-xs">
        <div class="{{ $isPicking ? '' : 'hidden' }}">
            <livewire:tables.organization.picking.picking-inventory-list wire:key="picking-list" />
        </div>
        <div class="{{ $isPicking ? 'hidden' : '' }}">
            <livewire:tables.organization.picking.batch-picking-list wire:key="batch-list" />
        </div>
    </div>

    @include('livewire.user.picking.modals.picking-product-modal')
    @include('livewire.user.picking.modals.biological-product-modal')
    @include('livewire.user.picking.modals.picking-batch-modal')
</div>
