<div>
    @if($showModal)
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" wire:click="closeModal">

            <!-- Modal Content -->
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden" wire:click.stop>

                <!-- Modal Header -->
                <div
                    class="bg-gradient-to-r from-[var(--color-primary-md)] to-[var(--color-primary-dk)] px-6 py-4 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">{{ $product->product_name ?? 'Product Details' }}</h2>
                                <p class="text-[var(--color-primary-xl)] opacity-90 text-sm">
                                    {{ $product->product_code ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="closeModal"
                            class="w-8 h-8 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="overflow-y-auto max-h-[calc(90vh-80px)] scrollbar-hidden">
                    @if($product)
                        <div class="p-6 space-y-6">

                            <!-- Product Image and Details -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Product Image -->
                                <div>
                                    <div
                                        class="bg-gradient-to-br from-[var(--color-primary-xl)] to-white rounded-xl p-4 h-64 flex items-center justify-center border border-[var(--color-primary-lt)] border-opacity-30">
                                        @php
                                            if (str_starts_with($product->image, 'http')) {
                                                $fullImageUrl = $product->image;
                                            } else {
                                                $images = json_decode($product->image, true);
                                                $imagePath = is_array($images) && !empty($images) ? $images[0] : $product->image;
                                                $fullImageUrl = asset('storage/' . $imagePath);
                                            }
                                        @endphp
                                        @if($product->image)
                                            <img src="{{ $fullImageUrl }}" alt="{{ $product->product_name }}"
                                                class="max-w-full max-h-full object-contain rounded-lg shadow-lg">
                                        @else
                                            <div class="text-center text-[var(--color-primary-md)]">
                                                <svg class="w-16 h-16 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-sm opacity-60">No Image Available</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="space-y-4">
                                    <!-- Status and Active -->
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($product->has_expiry_date)
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-medium bg-[var(--color-primary-xl)] text-[var(--color-primary-dk)] border border-[var(--color-primary-lt)]">
                                                Has Expiry Date
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Description -->
                                    @if($product->product_description)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $product->product_description }}</p>
                                        </div>
                                    @endif

                                    <!-- Product Information -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-800 mb-3 text-sm">Product Information</h4>
                                        <div class="grid grid-cols-1 gap-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Manufacture Code:</span>
                                                <span
                                                    class="font-medium text-gray-800">{{ $product->manufacture_code ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Category:</span>
                                                <span
                                                    class="font-medium text-gray-800">{{ ucfirst($product->categories?->category_name) ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Brand:</span>
                                                <span
                                                    class="font-medium text-gray-800">{{ ucfirst($product->brand?->brand_name) ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Supplier:</span>
                                                <span
                                                    class="font-medium text-gray-800">{{ ucfirst($product->supplier?->supplier_name) ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dimensions -->
                                    {{-- @if($product->weight || $product->length || $product->width || $product->height)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <h4 class="font-semibold text-gray-800 mb-2 text-sm">Dimensions & Weight</h4>
                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            @if($product->weight)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Weight:</span>
                                                <span class="font-medium">{{ $product->weight }}</span>
                                            </div>
                                            @endif
                                            @if($product->length)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Length:</span>
                                                <span class="font-medium">{{ $product->length }}</span>
                                            </div>
                                            @endif
                                            @if($product->width)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Width:</span>
                                                <span class="font-medium">{{ $product->width }}</span>
                                            </div>
                                            @endif
                                            @if($product->height)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Height:</span>
                                                <span class="font-medium">{{ $product->height }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif --}}
                                </div>
                            </div>

                            <!-- Recent Purchase Orders -->
                            <!-- Recent Purchase Orders -->
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-base font-semibold text-gray-800 mb-3">Recent Purchase Orders</h3>

                                @if(count($latestPurchaseOrders) > 0)
                                    <div class="space-y-1">
                                        @foreach($latestPurchaseOrders as $po)
                                            <div
                                                class="bg-white border border-gray-200 rounded px-3 py-2 flex items-center justify-between text-xs hover:shadow-sm transition-shadow">
                                                <div class="flex items-center gap-4 flex-wrap">
                                                    <span class="font-medium text-gray-800">{{ $po['po_number'] }}</span>
                                                    <span class="text-gray-600">|</span>
                                                    <span class="text-gray-600">Date : {{ $po['order_date'] }}</span>
                                                    <span class="text-gray-600">|</span>
                                                    <span class="text-gray-600 font-medium">Quantity: {{ $po['ordered_quantity'] }}/
                                                        {{ $po['ordered_unit'] }}</span>
                                                </div>
                                                <span class="px-2 py-0.5 rounded-full border text-xs {{ $po['status_badge_class'] }}">
                                                    {{ ucfirst($po['status']) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-6 text-gray-500">
                                        <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm">No purchase orders found for this product</p>
                                    </div>
                                @endif
                            </div>



                            <!-- Audit Information -->
                            {{-- <div class="bg-gray-50 rounded-lg p-3">
                                <h4 class="font-semibold text-gray-800 mb-2 text-sm">Audit Information</h4>
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Created By:</span>
                                        <span class="font-medium">{{ $product->creator->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Updated By:</span>
                                        <span class="font-medium">{{ $product->updater->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Created:</span>
                                        <span class="font-medium">{{ $product->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Updated:</span>
                                        <span class="font-medium">{{ $product->updated_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-4.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p>Product not found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>