<!-- Grid Layout -->
<div class="max-w-screen-5xl mx-auto px-2 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 sm:grid-cols-6 gap-3">
        <!-- Left Section (List & Search) -->
        <div
            class="sm:col-span-1 bg-white dark:bg-gray-800 shadow rounded-lg p-4 overflow-auto max-h-[500px]">
            <label for="default-search"
                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" wire:model.live.debounce.300ms="searchPurchaseOrder"
                    class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search ..." />
            </div>
            <ul class="mt-4 max-h-[400px] overflow-auto">
                @foreach ($purchaseOrderList as $po)
                    @php
                        $status = $po?->status;
                        $statusClasses = match ($status) {
                            'pending' => 'text-yellow-600 dark:text-yellow-400',
                            'ordered' => 'text-blue-600 dark:text-blue-400',
                            'partial' => 'text-orange-600 dark:text-orange-400',
                            'completed' => 'text-green-600 dark:text-green-400',
                            'cancel' => 'text-red-600 dark:text-red-400',
                            default => 'text-gray-600 dark:text-gray-400',
                        };
                    @endphp
                    <li wire:click="selectPo({{ $po->id }})"
                        class="{{ $statusClasses }} p-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 border-b border-slate-300 dark:border-slate-700 {{ ($purchaseOrder?->id == $po->id) ? 'bg-primary-md !text-white rounded' : '' }}">
                        {{ $po->purchase_oder_number }}
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Right Section (PO Details) -->
        <div class="sm:col-span-5 bg-white dark:bg-gray-800 shadow rounded-lg p-4 h-full transition-all duration-300">
            <div class="flex flex-end justify-end">
                <button wire:click="$set('viewPurchaseOrder', false)"
                    class="text-black text-2xl font-bold hover:text-gray-300 text-end">
                    &times;
                </button>
            </div>
            <div class="flex justify-between items-center mt-3 rounded bg-gray-100 dark:bg-primary-dk p-3">
                <h3 class="text-3xl font-semibold text-primary-dk dark:text-gray-200">
                    {{ $purchaseOrder->merge_id ?$purchaseOrder->merge_id : $purchaseOrder->purchase_oder_number }}
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
            <div class="flex p-3 text-primary-dk cursor-pointer gap-3 font-semibold mt-2">
                <div wire:click="$set('selectedTab', 'purchase')"
                    class="cursor-pointer px-2 py-1 border-b-2 transition-all duration-300 {{$selectedTab === 'purchase' ? 'border-primary-dk dark:text-white' : "dark:text-white text-black border-transparent"}}">
                    Purchase
                </div>
                @if ($purchaseOrder?->status !== 'completed')
                    <div wire:click="receiveProduct({{ $purchaseOrder->id }})"
                        class="cursor-pointer px-2 py-1 border-b-2 transition-all duration-300 {{$selectedTab === 'receive' ? 'border-primary-dk dark:text-white' : 'dark:text-white text-black border-transparent'}}">
                        Receive
                    </div>
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-6 p-3">

                <div class="sm:col-span-2 space-y-2 p-3">

                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold text-gray-900 dark:text-white">Date :</span>
                        {{ date(session('date_format', 'd M Y'), strtotime($purchaseOrder?->created_at)) }}
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
                <div class="sm:col-span-2 space-y-2 p-3">

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
            <div class="grid grid-cols-1 sm:grid-cols-6 p-3 gap-2">
                <div class="sm:col-span-4 bg-gray-100 dark:bg-gray-400 p-4 rounded shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                        <div class="sm:col-span-1 hidden">
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
                        <div class="sm:col-span-1">
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
                <div
                    class="sm:col-span-2 block max-w-sm p-6 rounded-lg bg-primary-md p-6 border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 max-h-[175px]">
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
                            // Combine city and state if both exist
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
            <div class="p-3 space-y-4">
    {{-- Desktop Table --}}
    <div class="hidden sm:block">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Product</th>
                    <th class="px-6 py-3">Unit</th>
                    <th class="px-6 py-3">Quantity</th>
                    <th class="px-6 py-3">Received</th>
                    <th class="px-6 py-3">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @if ($purchaseOrder?->purchasedProducts)
                    @foreach ($purchaseOrder->purchasedProducts as $product)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-normal">
                                ({{ $product->product->product_code }}) {{ $product->product->product_name }}
                            </td>
                            <td class="px-6 py-4">{{ $product->unit->unit_name }}</td>
                            <td class="px-6 py-4">{{ $product->quantity }}</td>
                            <td class="px-6 py-4">{{ $product->received_quantity }}</td>
                            <td class="px-6 py-4">
                                {{ session('currency', '$') }}{{ number_format($product->sub_total, 2) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="block sm:hidden space-y-4">
        @if ($purchaseOrder?->purchasedProducts)
            @foreach ($purchaseOrder->purchasedProducts as $product)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-4 bg-white dark:bg-gray-800 space-y-2">
                    <div class="text-sm text-gray-900 dark:text-white font-semibold">
                        ({{ $product->product->product_code }}) {{ $product->product->product_name }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Unit:</span>
                        {{ $product->unit->unit_name }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Quantity:</span>
                        {{ $product->quantity }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Received:</span>
                        {{ $product->received_quantity }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Total Price:</span>
                        {{ session('currency', '$') }}{{ number_format($product->sub_total, 2) }}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

        </div>
    </div>
</div>