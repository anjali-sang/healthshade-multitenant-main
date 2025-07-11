<div class="max-w-10xl mx-auto px-4">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-5">
        <section class="w-full border-b-2 pb-4 mb-6">
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Purchase Orders report') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('View your purchase order report with
                    advanced filters.') }}
                    </p>
                </div>
            </header>
        </section>
        <div class="text-xs">
            <livewire:tables.reports.purchase-report-list />
        </div>
    </div>
    <x-modal name="purchase_report_details_modal" width="w-full" height="h-auto" maxWidth="4xl" wire:model="showModal">
        <!-- Header with logo -->
        <header
            class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-center items-center bg-white dark:bg-gray-800 rounded-t-lg shadow-sm">
            <x-application-logo class="w-auto h-14 fill-current text-gray-700 dark:text-gray-300" />
        </header>

        <!-- Order info section -->
        <div
            class="flex items-center justify-between px-8 py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    <span class="text-blue-600 dark:text-blue-400">#{{$purchase_order?->purchase_oder_number}}</span>
                </h1>
            </div>
            <div class="text-right">
                <h2 class="text-md font-medium text-gray-600 dark:text-gray-400">
                    {{date(session('date_format', 'Y-m-d') . ' ' . session('time_format', 'H:i A'), strtotime($purchase_order?->created_at))}}
                </h2>
            </div>
        </div>

        <!-- Details section -->
        <div class="bg-white dark:bg-gray-800 px-2 py-6">
            <!-- Order details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Left column -->
                <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-lg shadow-sm border">
                    <h3 class="text-sm uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">Order Information
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Location </p>
                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                {{$purchase_order?->purchaseLocation->name}}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Shipping To</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                {{$purchase_order?->shippingLocation->name}}
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                <span
                                    class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{'Completed'}}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Middle column -->
                <div class="">

                </div>

                <!-- Right column -->
                <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-lg shadow-sm border">
                    <h3 class="text-sm uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">Supplier
                        Information</h3>
                    <div class="space-y-3">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Supplier </p>
                                <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{$purchase_order?->purchaseSupplier->supplier_name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{$purchase_order?->purchaseSupplier->supplier_email }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products table -->
            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="py-3.5 px-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                #</th>
                            <th scope="col"
                                class="py-3.5 px-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Product</th>
                            <th scope="col"
                                class="py-3.5 px-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Unit</th>
                            <th scope="col"
                                class="py-3.5 px-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Quantity</th>
                            <th scope="col"
                                class="py-3.5 px-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap ">
                                Sub Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($purchase_data as $index => $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-4 px-4 whitespace-wrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>
                                <td
                                    class="py-4 px-4 whitespace-wrap text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ $data?->product->product_name." (".$data?->product->product_code.")"}}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $data?->unit->unit_name }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $data?->quantity }}
                                </td>
                                <td
                                    class="py-4 px-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 text-right font-medium">
                                    {{session('currency', '$') . $data?->sub_total }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td colspan="4"
                                class="py-4 px-4 text-sm font-semibold text-gray-700 dark:text-gray-200 text-right">
                                Subtotal:</td>
                            <td class="py-4 px-4 text-sm font-semibold text-gray-700 dark:text-gray-200 text-right">
                            {{ session('currency', '$') .$purchase_order?->total }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"
                                class="py-4 px-4 text-base font-bold text-gray-800 dark:text-gray-100 text-right">
                                Total:
                            </td>
                            <td class="py-4 px-4 text-base font-bold text-gray-800 dark:text-gray-100 text-right">
                                {{ session('currency', '$') .$purchase_order?->total }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Footer with actions -->
        <footer
            class="px-8 py-5 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center rounded-b-lg">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <p>Notes: {{$purchase_order?->notes ?? 'No notes available'}}</p>
            </div>
            <div class="flex space-x-3">
                <button type="button"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors duration-200"
                    wire:click="closeModal">
                    Close
                </button>
            </div>
        </footer>
    </x-modal>
</div>