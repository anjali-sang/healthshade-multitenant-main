<div>
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center gap-4 pt-3 border-b-2 border-gray-900/10 pb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Master catalog') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Manage all products available in your organization.') }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full md:w-auto">
            <x-secondary-button class="flex justify-center items-center" x-data="{ loading: false }"
                x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'import-products-modal'); loading = false }, 1000)"
                x-bind:disabled="loading">
                <!-- Button Text -->
                <span x-show="!loading">
                    <span class="flex justify-center items-center gap-2">
                        <svg width="16px" height="16px" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor">
                            <g id="SVGRepo_bgCarrier" stroke-width="1"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                            </g>
                            <g id="SVGRepo_iconCarrier">
                                <defs>
                                    <style>
                                        .cls-1 {
                                            fill: currentColor;
                                        }
                                    </style>
                                </defs>
                                <title></title>
                                <g id="xxx-word">
                                    <path class="cls-1"
                                        d="M325,105H250a5,5,0,0,1-5-5V25a5,5,0,1,1,10,0V95h70a5,5,0,0,1,0,10Z">
                                    </path>
                                    <path class="cls-1"
                                        d="M325,154.83a5,5,0,0,1-5-5V102.07L247.93,30H100A20,20,0,0,0,80,50v98.17a5,5,0,0,1-10,0V50a30,30,0,0,1,30-30H250a5,5,0,0,1,3.54,1.46l75,75A5,5,0,0,1,330,100v49.83A5,5,0,0,1,325,154.83Z">
                                    </path>
                                    <path class="cls-1"
                                        d="M300,380H100a30,30,0,0,1-30-30V275a5,5,0,0,1,10,0v75a20,20,0,0,0,20,20H300a20,20,0,0,0,20-20V275a5,5,0,0,1,10,0v75A30,30,0,0,1,300,380Z">
                                    </path>
                                    <path class="cls-1" d="M275,280H125a5,5,0,1,1,0-10H275a5,5,0,0,1,0,10Z">
                                    </path>
                                    <path class="cls-1" d="M200,330H125a5,5,0,1,1,0-10h75a5,5,0,0,1,0,10Z">
                                    </path>
                                    <path class="cls-1"
                                        d="M325,280H75a30,30,0,0,1-30-30V173.17a30,30,0,0,1,30-30h.2l250,1.66a30.09,30.09,0,0,1,29.81,30V250A30,30,0,0,1,325,280ZM75,153.17a20,20,0,0,0-20,20V250a20,20,0,0,0,20,20H325a20,20,0,0,0,20-20V174.83a20.06,20.06,0,0,0-19.88-20l-250-1.66Z">
                                    </path>
                                    <path class="cls-1"
                                        d="M168.48,217.48l8.91,1a20.84,20.84,0,0,1-6.19,13.18q-5.33,5.18-14,5.18-7.31,0-11.86-3.67a23.43,23.43,0,0,1-7-10,37.74,37.74,0,0,1-2.46-13.87q0-12.19,5.78-19.82t15.9-7.64a18.69,18.69,0,0,1,13.2,4.88q5.27,4.88,6.64,14l-8.91.94q-2.46-12.07-10.86-12.07-5.39,0-8.38,5t-3,14.55q0,9.69,3.2,14.63t8.48,4.94a9.3,9.3,0,0,0,7.19-3.32A13.25,13.25,0,0,0,168.48,217.48Z">
                                    </path>
                                    <path class="cls-1"
                                        d="M179.41,223.15l9.34-2q1.68,7.93,12.89,7.93,5.12,0,7.87-2a6.07,6.07,0,0,0,2.75-5,7.09,7.09,0,0,0-1.25-4q-1.25-1.85-5.35-2.91l-10.2-2.66a25.1,25.1,0,0,1-7.73-3.11,12.15,12.15,0,0,1-4-4.9,15.54,15.54,0,0,1-1.5-6.76,14,14,0,0,1,5.31-11.46q5.31-4.32,13.59-4.32a24.86,24.86,0,0,1,12.29,3,13.56,13.56,0,0,1,6.89,8.52l-9.14,2.27q-2.11-6.05-9.84-6.05-4.49,0-6.86,1.88a5.83,5.83,0,0,0-2.36,4.77q0,4.57,7.42,6.41l9.06,2.27q8.24,2.07,11.05,6.11a15.29,15.29,0,0,1,2.81,8.93,14.7,14.7,0,0,1-5.92,12.36q-5.92,4.51-15.33,4.51a28,28,0,0,1-13.89-3.32A16.29,16.29,0,0,1,179.41,223.15Z">
                                    </path>
                                    <path class="cls-1"
                                        d="M250.31,236h-9.77L224.1,182.68h10.16l12.23,40.86L259,182.68h8Z">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        {{ __('Import') }}
                    </span>
                </span>

                <!-- Loader (Spinner) -->
                <span x-show="loading" class="flex justify-center items-center w-full">
                    <svg class="animate-spin h-5 w-5 text-primary-md" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-50" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z">
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
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z">
                        </path>
                    </svg>
                </span>
            </x-primary-button>
        </div>
    </div>
    <div class="text-xs">
        <livewire:tables.organization.master-catalog.master-catalog-list />
    </div>
    @include('livewire.organization.products.modals.add-product-modal')
    @include('livewire.organization.products.modals.product-form-modal')
    @include('livewire.organization.products.modals.import-products-modal')
    @include('livewire.organization.products.modals.edit-product-modal')

    <!-- Notifications Container -->
    <div class="fixed top-24 right-4 z-50 space-y-2">
        @foreach($notifications as $notification)
            <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }" x-init="
                                                                                    setTimeout(() => {
                                                                                        show = false;
                                                                                        $wire.removeNotification('{{ $notification['id'] }}');
                                                                                    }, 3000)
                                                                                " x-show="show"
                x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
                class="{{ $notification['type'] === 'success' ? 'border-green-800 text-green-800  bg-green-300' : 'bg-red-300 border-red-800 text-red-800' }} border-l-4 x-6 py-6 px-4  shadow-lg bg-white dark:bg-gray-700">
                <p>{{ $notification['message'] }}</p>
            </div>
        @endforeach
    </div>
    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="relative bg-white rounded-lg shadow-xl max-w-3xl max-h-[90vh] overflow-hidden">
            <div class="absolute top-0 right-0 p-2">
                <button onclick="closeImageModal()" class="p-1 bg-white rounded-full shadow hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <img id="modalImage" class="max-w-full max-h-[80vh] mx-auto" src="" alt="Product Image">
            </div>
        </div>
    </div>

    <script>
        function openImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }

        function openProductModal(id) {
            //console.log('openProductModal called with ID:', id);

            // Check if Livewire is available
            if (typeof Livewire !== 'undefined') {
                //console.log('Livewire is available, dispatching event...');
                Livewire.dispatch('openProductDetailBrowser', { id: id });
                //console.log('Event dispatched');
            } else {
                console.error('Livewire is not available!');
            }
        }
        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
                closeImageModal();
            }
        });
    </script>
</div>