{{-- <div class="w-full p-3 bg-white rounded-lg text-sm">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-3">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
            <h3 class="font-semibold text-gray-800 text-sm">Low Stock Alert</h3>
        </div>
        <span class="text-xs text-gray-500 bg-gray-50 px-2 py-0.5 rounded">
            {{ count($low_stock_products_list) }} items
        </span>
    </div>

    <!-- Products -->
    <div class="low-product-list space-y-1.5">
        @foreach($low_stock_products_list as $product)
            <div
                class="flex items-start gap-2 p-2 border border-transparent hover:border-red-100 hover:bg-red-50/30 rounded-md transition cursor-pointer">
                <!-- Info -->
                <div class="flex-1" onclick="openProductModal({{ $product->product?->id }})">

                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-gray-900 font-medium leading-tight truncate hover:text-red-700">
                                {{ \Illuminate\Support\Str::limit($product->product?->product_name, 40) }}
                            </h4>

                            <p class="text-xs text-gray-500">Code: {{ $product->product?->product_code }}</p>
                            <div class="flex flex-wrap items-center gap-1 mt-1 text-xs">
                                @if(isset($product->location) || isset($product->product?->location))
                                    <span
                                        class="flex items-center gap-1 px-1.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $product->location->name ?? $product->product->location->name ?? 'N/A' }}
                                    </span>
                                @endif

                                @if(isset($product->supplier) || isset($product->product->supplier))
                                    <span
                                        class="flex items-center gap-1 px-1.5 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h2M9 7h6m-6 4h6m-6 4h6">
                                            </path>
                                        </svg>
                                        {{ $product->supplier->name ?? $product->product->supplier->supplier_name ?? 'N/A' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Stock Count -->
                        <div class="text-xs px-2 py-0.5 bg-red-100 text-red-800 rounded-full font-medium whitespace-nowrap">
                            {{ $product->on_hand_quantity ?? 'Low' }} left
                        </div>
                    </div>

                    <!-- Progress -->
                    @if(isset($product->on_hand_quantity) && isset($product->alert_quantity))
                        <div class="mt-1">
                            @php
                                $percentage = min(($product->on_hand_quantity / max($product->alert_quantity, 1)) * 100, 100);
                            @endphp
                            <div class="h-1 w-full bg-gray-200 rounded-full">
                                <div class="h-1 bg-red-500 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if(count($low_stock_products_list) === 0)
        <div class="text-center py-6 text-sm text-gray-600">
            <div class="w-10 h-10 mx-auto mb-2 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            All products are well stocked!
        </div>
    @endif
</div>

<script>
    function openProductModal(id) {
        //console.log('openProductModal called with ID:', id);

        // Check if Livewire is available
        if (typeof Livewire !== 'undefined') {
            console.log('Livewire is available, dispatching event...');
            Livewire.dispatch('openProductDetailBrowser', { id: id });
            console.log('Event dispatched');
        } else {
            console.error('Livewire is not available!');
        }
    }

    function updateLowProductList(products) {
        console.log(products);
        const container = document.querySelector('.low-product-list');
        container.innerHTML = '';

        if (products.length === 0) {
            container.innerHTML = `
                <div class="text-center py-6 text-sm text-gray-600">
                    <div class="w-10 h-10 mx-auto mb-2 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    All products are well stocked!
                </div>`;
            return;
        }

        products.forEach(product => {
            const percentage = Math.min((product.on_hand_quantity / Math.max(product.alert_quantity, 1)) * 100, 100);
            const productHTML = `
                <div class="flex items-start gap-2 p-2 border border-transparent hover:border-red-100 hover:bg-red-50/30 rounded-md transition cursor-pointer">
                    <div class="flex-1"onclick="openProductModal('${product.product_id}')">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-gray-900 font-medium leading-tight truncate hover:text-red-700">
                                    ${product.product.product_name.length > 40 ? product.product.product_name.slice(0, 40) + '...' : product.product.product_name}</h4>
                                <p class="text-xs text-gray-500">Code: ${product.product.product_code}</p>
                                <div class="flex flex-wrap items-center gap-1 mt-1 text-xs">
                                    <span class="flex items-center gap-1 px-1.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        ${product.location.name ?? 'N/A'}
                                    </span>
                                    <span class="flex items-center gap-1 px-1.5 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h2M9 7h6m-6 4h6m-6 4h6">
                                            </path>
                                        </svg>
                                        ${product.product.supplier.supplier_name ?? 'N/A'}
                                    </span>
                                </div>
                            </div>
                            <div class="text-xs px-2 py-0.5 bg-red-100 text-red-800 rounded-full font-medium whitespace-nowrap">
                                ${product.on_hand_quantity} left
                            </div>
                        </div>
                        <div class="mt-1">
                            <div class="h-1 w-full bg-gray-200 rounded-full">
                                <div class="h-1 bg-red-500 rounded-full" style="width: ${percentage}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            const wrapper = document.createElement('div');
            wrapper.innerHTML = productHTML;
            container.appendChild(wrapper.firstElementChild);
        });
    };
</script> --}}