<div class="p-3">
    @if (!$viewPurchaseOrder)
        <div class="bg-white py-3 px-6 rounded">
            <section class="w-full border-b-2 pb-4 mb-6 bg-white">
                <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Manage Purchase orders') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Manage Purchase orders and update the invoices.') }}
                        </p>
                    </div>
                </header>
            </section>
            <div class="text-xs">
                <livewire:tables.user.purchase-list />
            </div>
        </div>
    @else
        <!-- View purchase order partial -->
        @include('livewire.admin.purchase.view-purchase-order')
    @endif
    @include('livewire.admin.purchase.modals.preview-modal')
    @include('livewire.admin.purchase.modals.upload-invoice-modal')
    @include('livewire.admin.purchase.modals.upload-ack-modal')
    @include('livewire.admin.purchase.modals.tracking-link-modal')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-url-in-new-tab', (event) => {
                window.open(event.url, '_blank');
            });
        });
    </script>
</div>