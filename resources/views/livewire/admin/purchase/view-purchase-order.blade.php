<!-- Grid Layout -->
<div class="max-w-screen-5xl mx-auto sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
        <!-- Right Section (PO Details) -->
        <div class="col-span-1 sm:col-span-2 md:col-span-4 lg:col-span-6 bg-white dark:bg-gray-800 shadow rounded-lg p-4 h-full transition-all duration-300">
            <div class="flex flex-end justify-end">
                <button wire:click="$set('viewPurchaseOrder', false)"
                    class="text-black text-2xl font-bold hover:text-gray-300 text-end">
                    &times;
                </button>
            </div>
            <div class="flex flex-wrap justify-between items-center mt-3 rounded bg-gray-100 dark:bg-primary-dk p-3">
                <h3 class="text-3xl font-semibold text-primary-dk dark:text-gray-200">
                    {{$purchaseOrder->purchase_oder_number ?? ''}}
                </h3>
                @php
                    $status = $purchaseOrder?->status;
                    $statusClasses = match ($status) {
                        'pending' => 'bg-yellow-100 border-2 border-yellow-800 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 py-2 px-3',
                        'ordered' => 'bg-blue-100 border-2 border-blue-800 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                        'partial' => 'bg-orange-100 border-2 border-orange-800 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                        'completed' => 'bg-green-100 border-2 border-green-800 text-green-800 dark:bg-green-900 dark:text-green-300',
                        'cancel' => 'bg-red-100 border-2 border-red-800 text-red-800 dark:bg-red-900 dark:text-red-300',
                        default => 'bg-gray-100 border-2 border-gray-800 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                    };
                @endphp
                <span class="text-sm font-medium me-2 px-2.5 py-0.5 rounded-full border {{ $statusClasses }}">
                    {{ ucfirst($status) ?? 'Unknown' }}
                </span>
            </div>
            @include('livewire.admin.purchase.invoice-section')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 p-3 gap-2">
                <div class="col-span-1 sm:col-span-3 lg:col-span-2 space-y-2 p-3">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold text-gray-900 dark:text-white">Date :</span>
                        {{ date(session('date_format', 'd M Y'), strtotime($purchaseOrder?->created_at)) }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold text-gray-900 dark:text-white">Organization :</span>
                        {{ $purchaseOrder?->organization->name }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold text-gray-900 dark:text-white">Location :</span>
                        {{ $purchaseOrder?->purchaseLocation->name }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold text-gray-900 dark:text-white">Created by :</span>
                        {{ $purchaseOrder?->createdUser->name }}
                    </p>
                </div>
                <div class="col-span-1 sm:col-span-3 lg:col-span-2 space-y-2 p-3">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold text-gray-900 dark:text-white">Total Products :</span>
                        {{ $purchaseOrder?->purchasedProducts->count() }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold text-gray-900 dark:text-white">Grand Total :</span>
                        {{ session('currency', '$') }}{{ number_format($purchaseOrder?->total, 2) }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-6 p-3 gap-2">
                <div class="col-span-1 lg:col-span-4 bg-gray-100 dark:bg-gray-400 p-4 rounded shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div class="col-span-1 hidden">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Billing Information</h3>
                            <div class="text-gray-800 dark:text-gray-800">
                                <p class="text-sm py-1">
                                    <span class="text-primary-dk">Location :</span>
                                    {{ $purchaseOrder->billingLocation->name ?? 'N/A' }}
                                </p>
                                <p class="text-sm py-1 ">
                                    <span class="text-primary-dk">Contact:</span>
                                    {{$purchaseOrder->billingLocation->email ?? 'N/A' }}
                                </p>
                                <p class="text-sm py-1">
                                    <span class="text-primary-dk">Bill to number:</span>
                                    #{{$purchaseOrder->bill_to ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Shipping Information</h3>
                            <div class="text-gray-800 dark:text-gray-800">
                                <p class="text-sm py-1">
                                    <span class="text-primary-dk">Location :</span>
                                    {{ $purchaseOrder->shippingLocation->name ?? 'N/A' }}
                                </p>
                                <p class="text-sm py-1 ">
                                    <span class="text-primary-dk">Contact:</span>
                                    {{$purchaseOrder->shippingLocation->email ?? 'N/A' }}
                                </p>
                                <p class="text-sm py-1">
                                    <span class="text-primary-dk">Ship to number:</span>
                                    #{{$purchaseOrder->bill_to ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-1 lg:col-span-2 block max-w-full p-6 rounded-lg bg-primary-md border border-gray-200 shadow-sm dark:border-gray-700 max-h-[175px]">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-50 ">
                        {{$purchaseOrder->purchaseSupplier->supplier_name ?? 'Supplier'}}
                    </h5>
                    <p class="font-normal text-gray-100 ">
                        {{$purchaseOrder->purchaseSupplier->supplier_email ?? 'Email'}}
                    </p>
                    <p class="font-normal text-gray-100">
                        {{$purchaseOrder->purchaseSupplier->supplier_phone ?? 'Phone'}}
                    </p>
                    <p class="font-normal text-gray-100 mt-2">
                        @php
                            $address = [];
                            $supplier = $purchaseOrder?->purchaseSupplier;
                            if ($supplier?->supplier_address) {
                                $address[] = e($supplier->supplier_address);
                            }
                            $cityState = collect([
                                $supplier?->supplier_city,
                                $supplier?->supplier_state
                            ])->filter()->join(', ');
                            if ($cityState) {
                                $address[] = e($cityState);
                            }
                            if ($supplier?->supplier_country) {
                                $address[] = e($supplier->supplier_country);
                            }
                            if ($supplier?->supplier_zip) {
                                $address[] = ' (' . e($supplier->supplier_zip) . ')';
                            }
                            $formattedAddress = implode(', ', $address);
                        @endphp
                        {{ !empty($address) ? $formattedAddress : 'No address available' }}
                    </p>
                </div>
            </div>
            <div class="p-3 overflow-x-auto">
                <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Product</th>
                            <th scope="col" class="px-6 py-3">Unit</th>
                            <th scope="col" class="px-6 py-3">Quantity</th>
                            <th scope="col" class="px-6 py-3">Received</th>
                            <th scope="col" class="px-6 py-3">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($purchaseOrder?->purchasedProducts)
                            @foreach($purchaseOrder->purchasedProducts as $product)
                                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-normal">
                                        ({{ $product->product->product_code}}) {{ $product->product->product_name}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $product->unit->unit_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $product->quantity}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $product->received_quantity}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ session('currency', '$') }}{{ number_format($product->sub_total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
